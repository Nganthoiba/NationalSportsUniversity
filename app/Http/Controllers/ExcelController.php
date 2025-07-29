<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Student;
use App\Models\UploadedExcelFile;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExcelController extends Controller
{
    private $headerRow = [];
    private $matchingFields = [];

    // Method to reverse the matching array
    private function reverseMatching($matching) {
        $reversed = [];
        foreach ($matching as $key => $value) {
            $reversed[$value] = $key;
        }
        return $reversed;
    }

    //Method to import excel file for students
    public function importStudents(Request $request) {        
        $message = '';
        $existingRecordMessage = '';
        $importedMessage = '';
        $insertCount = 0; 
        $updateCount = 0;    
        
        //Getting the mongodb client
        $client = DB::connection('mongodb')->getMongoClient();
        $session = $client->startSession();
        
        $request->validate([
                'excel_file' => 'required|mimes:xlsx',
                'matching_fields' => 'required|array',
                'matching_fields.*' => 'required|string',
            ]);
        
        try {
            // Start transaction
            $session->startTransaction();            

            $file = $request->file('excel_file');
            //Storing this file in a directory and then the path is to be stored in the database
            
            $uploadedFile = new UploadedExcelFile();
            $uploadedFile->file_name = $file->getClientOriginalName();
            // $uploadedFile->file_path = $file->store('uploads/excel_files', 'public');
            $uploadedFile->file_path = $file->storeAs('uploads/excel_files', $uploadedFile->file_name, 'public');
            $uploadedFile->uploaded_by = Auth::user()->_id;
            $uploadedFile->save();
            

            //Excel::import(new StudentsImport, $file);
            $this->matchingFields = $this->reverseMatching($request->input('matching_fields'));
            
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            //dd($worksheet);
            $data = [];
            foreach ($worksheet->getRowIterator() as $rowIndex => $row) {

                if ($rowIndex === 1) {
                    $this->headerRow = array_map(function($header){
                        if(isset($this->matchingFields[$header])) {
                            return $this->matchingFields[$header];
                        }
                        
                        $header = strtolower(trim($header));
                        $header = preg_replace('/\s+/', '_', $header);
                        $header = str_replace('.', '', $header);
                        return $header; 

                    },$this->getExcelRowData($row));
                    continue;
                }
                $rowData = $this->getExcelRowData($row);
                if(count($rowData) > 0)
                {
                    $data[] = $this->setUpKeyValuePair($this->getExcelRowData($row)); 
                }
            }
            // Check for duplicate Registration No
            $duplicates = collect($data)->pluck('registration_no')->duplicates();
            //dd($duplicates);
            
            if ($duplicates->isNotEmpty()) {
                throw new Exception("Duplicate Registration Nos found");
            }

            // First update the existing data
            $existingCount = 0;
            $existingData = [];
            $studentCount = count($data);
            for($i = 0; $i < $studentCount; $i++){
            //foreach ($data as $student) {
                $student = $data[$i];
                $recordExist = Student::where([
                    'registration_no' => trim($student['registration_no']),
                    //'status' => 0
                    ])->exists();                
                     
                if ($recordExist) 
                {
                    $existingData[] = $student;
                    $existingCount++;
                    $data[$i]['exists'] = true;
                }
                else{
                    $data[$i]['exists'] = false;
                }
            }

            if($existingCount > 0){ 
                $existingRecordMessage = $existingCount . ' record' . ($existingCount > 1 ? 's' : '') . ' already exist and no data was not imported.';
                $responseData = [
                        'message' => $existingRecordMessage,
                        'existingData' => $existingData,//which is not imported
                        'importedData' => [], //nothing is imported
                        'success' => false,
                        'error' => true,
                        'excelData' => $data
                ];
                                
                if($request->wantsJson()) {
                    return response()->json($responseData, 200);
                }
                return redirect()->back()->with($responseData);
            }          
            
            // Filter the data to exclude records with existing registration_no            
            $freshStudentData = [];
            foreach ($data as $student) {
                if (!Student::where('registration_no', $student['registration_no'])->exists()) {
                    $student['importedBy'] = Auth::user()->_id;
                    unset($student['exists']);
                    $freshStudentData[] = $student;
                }
            }            
            
            $insertCount = count($freshStudentData);
            
            if($insertCount > 0){
                $this->importStudentIntoDatabase($freshStudentData, $uploadedFile->_id);
                $importedMessage = ' '.$insertCount . ' student' . ($insertCount > 1 ? 's' : '') . ' imported successfully.';
            }
            
            //$message = (($insertCount + $updateCount) === 0)?'No record affected.':trim($importedMessage.' '.$updatedMessage);
            $message = ($insertCount === 0)?'No record affected.':trim($importedMessage);
            if($existingCount > 0){
                $message .= ' '.$existingRecordMessage;
            }                        

            //Now commit the transaction
            $session->commitTransaction();

            if($request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'existingData' => $existingData,//which is not imported
                    'importedData' => $freshStudentData, //which is imported
                    'success' => true,
                    'excelData' => $data
                ], 200);
            }
            return redirect()->back()->with([
                'message' => $message,
                'existingData' => $existingData, //which is not imported
                'importedData' => $freshStudentData, //which is imported
                'success' => true,
                'excelData' => $data
            ]);

        }
        catch (\Exception $e) {
            //Error happens, so abort the transaction
            $session->abortTransaction();

            if($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error' => true
                ], 403);
            }

            return redirect()->back()->with([
                'message' => 'Sorry an error has occured. '.$e->getMessage(),
                'success' => false,
                'error' => true
            ]);
        }
        finally{            
            $session->endSession();
            $session = null;
            $client = null;
        }        
    }

    private function getExcelRowData($row) {
        $rowData = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        return $rowData;
    }

    private function setUpKeyValuePair($data) {
        
        if(is_array($data)){
            
            $keyValuePair = [];
            foreach ($this->headerRow as $index => $header) {
                //$header = str_replace(' ', '_', $header);

                if(isset($data[$index])){
                    $keyValuePair[$header] = trim($data[$index]);
                }
                else{
                    $keyValuePair[$header] = ''; 
                }
                
            }
            return $keyValuePair;
        }
        return $data;
    }


    // Insert New Students
    private function importStudentIntoDatabase($data, $fileId) {
        // Prepare the data for bulk insert        
        $studentsData = array_map(function ($data) use ($fileId) {            
            // Check if the course is a sports course
            // Retrieve sport required courses from the config
            //$sport_required_courses = config('sports.sport_required_courses');

            // Check if the course is a sports course
            //$sport_required = in_array($data['course'], $sport_required_courses);
            
            return array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 0,
                'created_by' => Auth::user()->_id,
                //'sport_required' => $sport_required,
                'uploaded_file_id' => $fileId,
                'university_id' => Auth::user()->university_id,
            ]);
        }, $data);

        if (!empty($studentsData)) {

            foreach($studentsData as $student){
                try{
                    Student::insert($student);
                }
                catch(\Exception $e){
                    echo $e->getMessage();
                    echo "Error occurs at ".json_encode($student);
                    throw $e;
                }
            }
        }
    }

    // Update Existing Students
    private function updateStudentData($dataList) {

        $studentsData = array_map(function ($data) {
            return array_merge($data, [                
                'updated_by' => Auth::user()->_id,
            ]);
        }, $dataList);

        if (!empty($studentsData)) {
            return Student::upsert($studentsData, ['registration_no'], [
                'name_of_students', 'name_of_students_in_hindi', 'gender', 'father_name', 'mother_name', 
                'batch', 'month', 'month_in_hindi', 'year', 'course', 'course_in_hindi', 'department', 
                'department_in_hindi', 'sports', 'sports_in_hindi', 'grade', 'updated_at', 'sl_no', 'updated_by'
            ]);
        }
        return 0; //means no data has been affected
    }

    public function importStudentRecords(Request $request){
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv,xls'
        ]);
        try{
            $import = new StudentsImport();
            Excel::import($import, $request->file('excel_file'));
            $updatedMessage = ($import->updatedCount > 0)?"{$import->updatedCount} record(s) have been updated":'';
            $importedMessage = ($import->importedCount > 0)?"{$import->importedCount} record(s) have been imported":'';
            return back()->with([
                'success'=>true, 
                'message'=>trim($importedMessage.' '.$updatedMessage)
            ]);
        }
        catch(Exception $e){
            return back()->with([
                'success'=>false, 
                'message'=>'An error has occured while importing student records form the uploaded excel file. '.$e->getMessage(),
                'error'=>$e
            ]);
            
        }
        
    }

}
