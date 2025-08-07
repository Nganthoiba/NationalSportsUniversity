<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DataChangeRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\FormStructure;
use App\Models\Course;
use App\Models\Department;
use App\Models\DataChange;
use App\Models\Sport;
use App\Models\Gender;
use App\Models\Grade;
use TCPDF;
use TCPDF_FONTS;
use Exception;
use setasign\Fpdi\Tcpdf\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use MongoDB\BSON\Regex;
use App\Http\Requests\EditStudentRequest;
use App\CustomLibrary\JWSExtractor;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\CustomLibrary\Month;
use App\Http\Requests\AddStudentRequest;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function addNewStudent(AddStudentRequest $request){
        if(Auth::user()->hasPermission('student_data_entry') == false){
                /* return redirect()->back()->with([
                    'message' => 'You do not have permission to add new student record.',
                    'status' => 'error',
                    'error' => true,
                ]); */
            return view('layout.errorMessage',[
                'title' => 'Not permitted',
                'message' => 'Sorry, you do not have permission to add new student record.'
            ]);
        }

        if($request->isMethod('POST')){           

            try{
                $student = new Student();
                $data = $request->validated(); // Use validated data from the request
                $student->name_of_students = $data['name_of_students'];
                $student->name_of_students_in_hindi = $data['name_of_students_in_hindi'];
                list($monthEng, $monthHindi) = explode('~', $data['month']);
                $student->month = $monthEng;
                $student->month_in_hindi = $monthHindi;
                $student->year = $data['year'];
                $student->batch = $data['batch'];

                $course = Course::find($data['course']);
                $student->course = $course->course_name;
                $student->course_in_hindi = $course->course_in_hindi;

                $department = Department::find($data['department']);
                $student->department = $department->dept_name;
                $student->department_in_hindi = $department->dept_name_in_hindi;

                $sports = Sport::find($data['sports']);
                $student->sports = $sports->sport_name;
                $student->sports_in_hindi = $sports->sport_name_in_hindi;

                $student->father_name = $data['father_name'];
                $student->mother_name = $data['mother_name'];
                $student->grade = $data['grade'];
                $student->gender = $data['gender'];

                $student->registration_no = $this->generateRegistrationNo(
                    $data['batch'], 
                    $course->short_form
                );

                $student->status = 0; // 0 means not approved yet
                $student->save();
                return redirect()->back()->with([
                    'message' => 'Student record added successfully with registration number: '.$student->registration_no,
                    'status' => 'success',
                    'success' => true
                ]);
            }
            catch(Exception $e){
                return redirect()->back()->with([
                    'message' => 'An error has occured while adding student record. '.$e->getMessage(),
                    'status' => 'error',
                    'error' => true
                ]);
            }            
        }

        $compulsoryFields = Student::$compulsoryFields; 
        $courses = Course::where('enabled', true)->get();
        $departments = Department::all(); 
        $sports = Sport::all();
        $grades = \App\Models\Grade::orderBy('grade')->get();  
        $genders = Gender::all(); 
        return view('student.addNewStudent',[
            'compulsoryFields' => $compulsoryFields,
            'months' => Month::$months,
            'courses' => $courses,
            'departments' => $departments,
            'sports' => $sports,
            'grades' => $grades,
            'genders' => $genders,
        ]);
    }

    //function to generate registration number
    private function generateRegistrationNo($batch, $course_short_name){

        $batch_parts = explode('-', $batch);
        if(count($batch_parts) < 2){
            throw new Exception("Invalid batch format. Expected format: 'Batch-Year'");
        }

        $base_year = substr($batch_parts[0], -2); // Extract the year from the batch
        // Preparing a base registration number format
        $base_registration_no = strtoupper("{$base_year}/NSU_{$course_short_name}");

        // retrieve all existing registration numbers
        $regd_nos = Student::where('registration_no', 'like', "%{$base_registration_no}%")
            ->pluck('registration_no')
            ->map(function ($regd_no) use ($base_registration_no) {
                // Extract the numeric part after the base registration number
                $numeric_part = str_replace($base_registration_no, '', $regd_no);
                return (int) preg_replace('/[^0-9]/', '', $numeric_part); // Remove non-numeric characters
            })->toArray();  

        // Now find the maximum numeric part
        $max_numeric_part = !empty($regd_nos) ? max($regd_nos) : 0;
        // Increment the maximum numeric part to generate a new unique registration number
        $new_numeric_part = $max_numeric_part + 1;
        //Now fit the new numeric part into the registration number format
        //$registrationNo = $base_registration_no . '-' . str_pad($new_numeric_part, 3, '0', STR_PAD_LEFT); // Ensure it is 3 digits long
        return $base_registration_no . '/' .$new_numeric_part; // Ensure it is 3 digits long     
    }

    public function createStudent(Request $request){

        $structure = FormStructure::where('form_id', 1)->first();
        if($request->isMethod("POST"))
        {
            $data = $request->all();            
            unset($data["_token"]);
            try{
                $student = new Student();
                foreach($data as $key => $value){
                    if($key == "_token"){
                        continue;
                    }
                    $student->{$key} = $value;
                }
                if(count($data) > 0){
                    $student->save();
                }
                $this->updateFormStructure($structure, array_keys($data));
            }
            catch(Exception $e){
                return response()->json([
                    'message' => 'An error has occured while saving student record. '.$e->getMessage(),
                    'error' => $e,
                ], 403);
            }
            return response()->json([
                'message' => 'Student record saved successfully'
            ]);
        }
        
        return view('student.createStudent',[
            'structure' => $structure
        ]);
    }

    private function updateFormStructure($structure, $fields = []){
        if(count($fields) == 0){
            return;
        }
        $new_fields = [];
        foreach($fields as $field){
            if(!in_array($field, $structure->fields)){
                $new_fields[] = $field;
            }
        }
        $structure->fields = array_merge($structure->fields, $new_fields);
        $structure->save();
    }

    //method to get student record
    public function displayStudents(Request $request, string $status='')
    {
        $courses = Course::where('enabled', true)->get();
        $departments = Department::all();
        $sports = Sport::all();

        return view('student.displayStudents', [
            'courses' => $courses,
            'departments' => $departments,
            'sports' => $sports,
            'status' => $status,
            'compulsoryFields' => Student::$compulsoryFields,
            'grades' => Grade::all()
        ]);
    }

    // method to retrieve students and response back the 
    // data in such a way that it can be displayed in a datatable
    public function getStudents(Request $request){
        $draw = intval($request->input('draw'));
        $start = intval($request->input('start'));
        $length = intval($request->input('length'));
        $search = $request->input('search')['value'] ?? '';
        $searchableColumns = array_filter($request->input('columns', []), function ($col) {
            return isset($col['searchable']) && $col['searchable'] == 'true' && !is_null($col['search']['value']);
        });
                
        $query = Student::query();
        if(isset($request->university_id)){
            $query->where('university_id', $request->university_id);
        }

        //searchable fields
        $studentFields = [
            'name_of_students', 
            'gender',
            'name_of_students_in_hindi', 
            'registration_no', 
            'department', 
            'department_in_hindi', 
            'course', 
            'course_in_hindi', 
            'batch', 
            'year', 
            'sports',
            // Add more fields as needed
        ];        

        // Apply search (if any)        
        if (!empty($search)) {
            $query->where(function ($q) use ($search, $studentFields) {
                foreach ($studentFields as $field) {
                    $q->orWhere($field, 'like', '%' . $search . '%');
                }
            });
        }
        $statusSearch = [];
        foreach ($searchableColumns as $col) {
            $columnName = $col['data'] ?trim($col['data']):"";
            $searchValue = trim($col['search']['value']);// ?trim($col['search']['value']):"";
            
            // Check if the column is searchable and has a search value          
            if ($searchValue!=="" && $columnName!=="") {
                if (in_array($columnName, ['gender', 'grade', 'department', 'course', 'year'])) {
                    $query->where($columnName, $searchValue);
                }
                else if(in_array($columnName, ['name_of_students', 'sports', 'month'])){
                    $query->where(function ($q) use ($columnName, $searchValue) {
                        $q->where("{$columnName}_in_hindi", 'like', '%' . $searchValue . '%')
                        ->orWhere($columnName, 'like', '%' . $searchValue . '%');
                    });
                }
                else if($columnName == "status"){
                    if(is_numeric($searchValue)){
                        $query->where('status', intval($searchValue));                        
                    }
                }
                else{
                    $query->where($columnName, 'like', '%' . $searchValue . '%');
                }                
            }            
        }
        
        // Total before filtering
        $total = Student::count();

        // Total after filtering
        $filtered = $query->count();

        // Ordering
        if ($order = $request->input('order')[0] ?? null) {
            $columnIndex = $order['column'];
            $columnName = $request->input("columns.$columnIndex.data");
            $dir = $order['dir'];
            $query->orderBy($columnName, $dir);
        }

        // Pagination
        $students = $query->skip($start)->take($length)->get();
        // Add DT_RowIndex manually
        $data = [];
        foreach ($students as $index => $student) {
            $row = $student->toArray();
            $row['DT_RowIndex'] = $start + $index + 1; // 1-based index
            $row['view_details'] = route('viewStudent', $student->_id);
            //$row['edit_student'] = ($student->status == 1)?route('requestForChange', $student->_id):route('editStudent', $student->_id);
            $row['edit_student'] = ($student->status != 2)?route('requestForChange', $student->_id):'#';// status = 2 means the student record is already
            //in the process of record change so no further change in the student record will be allowed untill it is either approved or discarded
            $data[] = $row;
        }
        // Format response
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
            'searchableColumns' => $searchableColumns,
            'search' => $search,
        ]);
    }


    //method to view a student record
    public function viewStudent(Request $request, $id){
        $student = Student::findOrFail($id);
        
        if(!$student){            
            abort(404, 'Student not found.');
        }

        $page = $request->get('page', 1);
        $status = $request->get('status', "");

        //Check if there is already a request for changing the student data
        if($student->status == 2 /*&& Auth::user()->isRole(['admin'])*/){
            //Finding the request for Changing student data
            $dataChange = DataChange::where('registration_no', $student->registration_no)->where('status', 'pending')->first();
            return $this->viewChangeDetails($student, $dataChange);
        }

        $isSigned = $student->isSigned();
        $isSignatureValid = $student->isSignatureValid();
        $isDataIntegrityVerified = $student->verifySignedData();
        
        $signerName = $student->getSignerName();
        $jws = $student->getJWS();
        $payload = $jws==null?[]:JWSExtractor::decodePayload($jws);
        
        return view('student.viewStudent',compact('student', 
        'isSigned', 
        'isSignatureValid', 
        'isDataIntegrityVerified',
        'signerName','jws'))->with([
            'page' => $page,
            'status' => $status
        ]);
    }

    //method to see the data change details, and if the authenticated user is admin then he will approve the changes
    //Only for pending
    private function viewChangeDetails($student, $dataChange){       
        
        $compulsoryFields = Student::$compulsoryFields;
        $newStudentData = clone $student;//Student::find($student->id);
        foreach($dataChange->records_to_be_changed as $key => $val){
            $newStudentData->{$key} = $val;
        }

        $requester = User::find($dataChange->requested_by);

        return view('student.dataChangeRequest',[
            'student' => $student,
            'dataChange' => $dataChange,
            'fields' => $compulsoryFields,
            'newStudent' => $newStudentData,
            'requestedBy' => $requester->full_name,
        ]);
    }

    //method to approve a student record
    public function approveStudent(Request $request){
        $user = auth()->user();
        //Only admin can approve student record
        if($user->isRole(['admin']) == false){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'You are not authorized to approve student record'
                ], 403);
            }
            return redirect()->route('displayStudents')->with([
                'message' => 'You are not authorized to approve student record',
                'status' => 'error'
            ]);
        }

        // Validation
        $request->validate([
            'studentId' => 'required|exists:students,_id',
            'jws' => 'required|string'
        ]);

        try{
            $studentId = $request->post('studentId');
            $jws = $request->post('jws'); //Signed Student Information

            $student = Student::find($studentId);            
            
            $student->status = 1;
            /*
            1 means approved
            0 means not approved yet
            2 means pending for reapproval of the record which is already approved
            3 means rejected
            */
            $approved_by = [
                'approver_id' => auth()->user()->_id,
                'approved_at' => now(),
                'jws' => $jws
            ];
            $student->approved_by = $approved_by;
            $student->save();

            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Student record approved successfully'
                ]);
            }
            return redirect()->route('viewStudent', $studentId)->with([
                'message' => 'Student record approved successfully',
                'status' => 'success'
            ]);
        }
        catch(Exception $e){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'An error has occured while approving student record. '.$e->getMessage()
                ], 403);
            }

            return redirect()->route('viewStudent', $studentId)->with([
                'message' => 'An error has occured while approving student record. '.$e->getMessage(),
                'status' => 'error'
            ]);
        }
        
    }

    //method to approve the data changes of a record of a particular student
    public function approveStudentDataChanges(Request $request){
        $user = auth()->user();
        //Only admin can approve student record
        if($user->isRole(['admin']) == false){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'You are not authorized to approve student record'
                ], 403);
            }
            return redirect()->route('displayStudents')->with([
                'message' => 'You are not authorized to approve student record',
                'status' => 'error'
            ]);
        }

        // Validation
        $request->validate([
            'studentId' => 'required|exists:students,_id',
            'jws' => 'required|string'
        ]);

        //Getting the mongodb client
        $client = DB::connection('mongodb')->getMongoClient();
        $session = $client->startSession();

        try{
            $studentId = $request->post('studentId');
            $jws = $request->post('jws'); //Signed Student Information

            // Start transaction
            $session->startTransaction();

            $student = Student::find($studentId);            
            
            //Request for Changing student data is found
            $dataChange = DataChange::where('registration_no', $student->registration_no)->where('status', 'pending')->first();          
            /*
            foreach($dataChange->records_to_be_changed as $key => $val){
                $student->{$key} = $val;
            }*/
            
            $data = JWSExtractor::decodePayload($jws);
            foreach($data as $key => $val){
                $student->{$key} = $val;
            }
            
            $student->status = 1;
            /*
            1 means approved
            0 means not approved yet
            2 means pending for reapproval of the record which is already approved
            3 means rejected
            */
            $approved_by = [
                'approver_id' => auth()->user()->_id,
                'approved_at' => now(),
                'jws' => $jws
            ];
            $student->approved_by = $approved_by;  

            $student->save();

            $dataChange->status = "approved";
            $dataChange->reviewed_by = auth()->user()->_id;
            $dataChange->date_of_review = now();
            $dataChange->save();

            //Now commit the transaction
            $session->commitTransaction();

            if($request->wantsJson()){
                return response()->json([
                    'message' => 'The changes in the student record bearing registration number '.$student->registration_no.' has been approved successfully'
                ]);
            }
            return redirect()->route('viewStudent', $studentId)->with([
                'message' => 'Student record approved successfully',
                'status' => 'success'
            ]);
        }
        catch(Exception $e){
            //Error happens, so abort the transaction
            $session->abortTransaction();

            if($request->wantsJson()){
                return response()->json([
                    'message' => 'An error has occured while approving student record. '.$e->getMessage()
                ], 403);
            }

            return redirect()->route('viewStudent', $studentId)->with([
                'message' => 'An error has occured while approving student record. '.$e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
    

    //method to show certificate of a student
    public function showCertificate(Request $request, $studentId, $type = ''){
        $student = Student::find($studentId);
        $data = [
            'student' => $student
        ];

        if(!isset($student->status) || in_array($student->status, [0, 2])){
            //status = 0 means not approved yet, status = 2 means pending for reapproval
            //of the record which is already approved

            $messages = [
                0 => 'Student record is not approved yet.',
                2 => 'Student record is pending for reapproval.'
            ];

            $data = array_merge($data, [
                'message' => $messages[$student->status] ?? 'Student record is not approved yet.',
                'status' => 'warning'
            ]);
        }
        else{
            $data = array_merge($data, [
                'message' => 'Student record is approved.',
                'status' => 'success'
            ]);
        }
        
        // ini_set('max_execution_time', 300);
        if($type == 'pdf'){
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                //'default_font' => 'NotoSansDevanagari', // Change this to your font name
                'autoScriptToLang' => true,  // Automatically detects Hindi script
                'autoLangToFont' => true,  // Ensures correct font is applied
            ]);
    
            // Load Hindi content
            $html = View::make('student.template.certificateDoc', $data)->render();
            $mpdf->WriteHTML($html);
    
            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, 'NSUCertificate.pdf');
            // $pdf = Pdf::loadView('student.template.certificateDoc', $data);
            // return $pdf->download('NSUCertificate.pdf');
        }
        return view('student.template.certificate', $data);
    }    

    //method to show student details. This can be used for verifying student record by users
    public function showStudentDetails(Request $request, $id) {
        $student = Student::find($id);
    
        if (!$student) {
            return $this->handleStudentResponse(
                $request,
                'Student record not found',
                'warning',
                404
            );
        }
    
        if (isset($student->status) && $student->status == 1) {
            return $this->handleStudentResponse(
                $request,
                'Student record is approved',
                'success',
                200,
                $student
            );
        }
    
        return $this->handleStudentResponse(
            $request,
            'Student record is not approved yet',
            'warning',
            403
        );
    }

    private function handleStudentResponse($request, $message, $status, $httpCode = 200, $student = null) {
        if ($request->wantsJson()) {
            $response = ['message' => $message];
            if ($student) {
                $response['student'] = $student;
            }
            return response()->json($response, $httpCode);
        }
    
        $viewData = ['message' => $message, 'status' => $status];
        if ($student) {
            $viewData['student'] = $student;
        }
        return view('student.showStudentDetails', $viewData);
    }


    

    // Method to delete a student record
    public function deleteStudent($id){
        $student = Student::find($id);
        if(is_null($student) || empty($student)){
            return response()->json([
                'message' => 'No record available for the given student ID',
                'status' => false
            ],404);
        }

        if($student->status == 1){
            return response()->json([
                'message' => 'Approved record cannot be deleted.',
                'status' => false
            ], 403);
        }

        try{
            $student->delete();
        }
        catch(Exception $e){
            return response()->json([
                'message' => 'An error has occurred while deleting student record. '.$e->getMessage(),
                'status' => false
            ], 500);
        }
        return response()->json([
            'message' => 'Student record deleted successfully',
            'status' => true
        ], 202);
    }

    // End point to request for a change in a record of a student
    public function requestForChange($id){
        $student = Student::find($id);
        if(is_null($student) || empty($student)){
            //return "Student record not available";
            abort(404, 'Student not found.');
        }

        //Check if there is already an ongoing process for data changes for the student
        $dataChange = DataChange::where('registration_no', $student->registration_no)->where('status', 'pending')->first();
        if(!empty($dataChange)){
            //abort(403, 'A data change process for this student is already in progress and must be completed before initiating another.');
            return response()->view('layout.errorMessage', [
                'title' => 'Unable to Proceed',
                'message' => 'A request fo data change is already in progress for this student. Please wait until it is completed.',
                'type' => 'warning',
            ], 409);        
        }

        $compulsoryFields = Student::$compulsoryFields;
        
        unset($compulsoryFields['name_of_students_in_hindi']);
        unset($compulsoryFields['course_in_hindi']);
        unset($compulsoryFields['department_in_hindi']);
        unset($compulsoryFields['month_in_hindi']);
        unset($compulsoryFields['sports_in_hindi']);
        unset($compulsoryFields['registration_no']);

        // redefining few fields for the request form
        $compulsoryFields['name_of_students'] = ['DisplayAs' => 'Students Name (छात्रों_का_नाम)'];
        $compulsoryFields['course'] = ['DisplayAs' => 'Course (पाठ्यक्रम)'];
        $compulsoryFields['department'] = ['DisplayAs' => 'Department (विभाग)'];
        $compulsoryFields['month'] = ['DisplayAs' => 'Month (महीना)'];
        $compulsoryFields['sports'] = ['DisplayAs' => 'Sports (खेल)'];
        

        $courses = Course::where('enabled', true)->get();
        $departments = Department::all();
        $months = \App\CustomLibrary\Month::$months;
        $sports = \App\Models\Sport::all();
        $grades = \App\Models\Grade::orderBy('grade')->get();
        $genders = Gender::all(); 
        
        return view('student.requestForChange',[
            'student' => $student,
            'fields' => $compulsoryFields,
            'courses' => $courses,
            'departments' => $departments,
            'sports' => $sports,
            'months' => $months,
            'grades' => $grades,
            'genders' => $genders,
        ]);
    }

    // End point to submit the request for changes in student data
    public function submitRequestForChange(DataChangeRequest $request){
        $data = $request->validated();
        try{
            //First check if there is already a request submitted for record change and the status is still pending
            $existDataChange = DataChange::where('registration_no', $data['registration_no'])
            ->where('status', 'pending')->exists();
            if($existDataChange){
                return response()->json([
                    'message' => ' There is already an existing request for record changing of the reqistration number '.$data['registration_no']. 
                    ' You cannot submit your request unless the earlier request is either approved or rejected by the concerned authority.'
                ], 403);
            }
        
            $currentStudent = Student::where('registration_no', $data['registration_no'])->first();
            // Save to database
            $changeRequestData = [
                'registration_no' => $data['registration_no'],
                'reason_of_change' => $data['reason_of_change'],
                'requested_by' => auth()->id() ?? 'web_user',
                'date_of_request' => now(),
                'status' => 'pending',
                'old_student_data' => $currentStudent
            ];



            foreach($data['records_to_be_changed'] as $field){
                switch($field){
                    case 'name_of_students':                                               
                        $changeRequestData['records_to_be_changed'][$field] = $data[$field];
                        $nameInHindi = $request->post('name_of_students_in_hindi', null);
                        if(!is_null($nameInHindi) || trim($nameInHindi) !== ""){
                            $changeRequestData['records_to_be_changed']['name_of_students_in_hindi'] = $nameInHindi;
                        }
                        
                        //Check if the old value and new value are same, if it is found same, then we are not allowing to submit
                        if(trim($currentStudent->{$field}) == trim($data[$field])){
                            return response()->json([
                                'message' => 'Change not allowed because there is no change in the name of student (english).'
                            ], 403);
                        }

                        if(trim($currentStudent->name_of_students_in_hindi) == trim($nameInHindi)){
                            return response()->json([
                                'message' => 'Change not allowed because there is no change in the name of student in hindi.'
                            ], 403);
                        }                        
                        break;

                    case 'course':
                        $course = Course::find($data['course']);
                        if($course){
                            if(trim($currentStudent->course) == trim($course->course_name)){
                                return response()->json([
                                    'message' => 'Change not allowed because you have selected the same earlier '.$field.' for the student.'
                                ], 403);
                            }
                            $changeRequestData['records_to_be_changed']['course'] = $course->course_name;
                            $changeRequestData['records_to_be_changed']['course_in_hindi'] = $course->course_in_hindi;                            
                        }
                        else{
                            return response()->json([
                                'message' => 'Course is not available, you might chosen wrong course.'
                            ], 404);
                        }
                        break;

                    case 'department':
                        $department = Department::find($data['department']);
                        if($department){
                            if(trim($currentStudent->department) == trim($department->dept_name)){
                                return response()->json([
                                    'message' => 'Change not allowed because you have selected the same earlier '.$field.' for the student.'
                                ], 403);
                            }
                            $changeRequestData['records_to_be_changed']['department'] = $department->dept_name;
                            $changeRequestData['records_to_be_changed']['department_in_hindi'] = $department->dept_name_in_hindi;                            
                        }
                        break;

                    case 'sports':
                        $sport = Sport::find($data['sports']);
                        if($sport){
                            if(trim($currentStudent->sports) == trim($sport->sport_name)){
                                return response()->json([
                                    'message' => 'Change not allowed because you have selected the same earlier '.$field.' for the student.'
                                ], 403);
                            }
                            $changeRequestData['records_to_be_changed']['sports'] = $sport->sport_name;
                            $changeRequestData['records_to_be_changed']['sports_in_hindi'] = $sport->sport_name_in_hindi;                            
                        }
                        break;

                    case 'month':
                        $monthParts = explode('~', $data['month']);
                        if(count($monthParts) == 2){
                            $changeRequestData['records_to_be_changed']['month'] = $monthParts[0];
                            $changeRequestData['records_to_be_changed']['month_in_hindi'] = $monthParts[1];
                            //Check if the old value and new value are same, if it is found same, then we are not allowing to submit
                            if($currentStudent->month == $monthParts[0]){
                                return response()->json([
                                    'message' => 'Change not allowed because you have selected the same earlier '.$field.' for the student.'
                                ], 403);
                            }
                        }
                        break;

                    default:
                        $changeRequestData['records_to_be_changed'][$field] = $data[$field];
                        //Check if the old value and new value are same, if it is found same, then we are not allowing to submit
                        if(trim($currentStudent->{$field}) == trim($data[$field])){
                            $fieldName = str_replace('_',' ',$field);
                            return response()->json([
                                'message' => 'Change not allowed because there is no change in '.$fieldName.' for the student.'
                            ], 403);
                        }
                }                
            }

            $res = DataChange::create($changeRequestData);
            if($res){
                $currentStudent->status = 2;
                $currentStudent->save();
            }     

            return response()->json([
                'message' => "You have submitted your data change request for the student bearing registration number {$data['registration_no']}  successfully.",
                'submittedData' => $data,
                'changedReqData' => $changeRequestData, 
            ], 201);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage().' Line no: '.$e->getLine(),
                'message' => "An error has occured while submiting your data change request for the registration number ".$data['registration_no'],
                'data' => $data
            ], 500);
        }        
    }

    //Method for viewing data change history
    public function displayDataChange(Request $request){
        $dataChanges = DataChange::orderByDesc('created_at')->get();
        
        $newCollection = $dataChanges->map(function ($dataChange){
            $requester = User::find($dataChange->requested_by);
            $dataChange->requesterName = empty($requester)?'Not Found':$requester->full_name;

            if(!is_null($dataChange->reviewed_by)){
                $reviewer = User::find($dataChange->reviewed_by);
                $dataChange->reviewerName = empty($reviewer)?'Not Found':$reviewer->full_name;                
            }
            else{
                $dataChange->reviewerName = $dataChange->status == "pending"?"Not yet reviewed":"N/A";
            }
            return $dataChange;
        });
        return view('student.dataChangeHistory', [
            'dataChanges' => $newCollection
        ]);
    }

    //Method to display the detail of a record changes made for a student
    public function viewDataChangeDetail($id){
        $dataChange = DataChange::find($id);

        if($dataChange->status == "pending"){            
            $student = Student::where('registration_no', $dataChange->registration_no)->first();
            return $this->viewChangeDetails($student, $dataChange);            
        }

        $dataChange->requesterName = User::find($dataChange->requested_by)->full_name;
        if(!is_null($dataChange->reviewed_by)){
            $dataChange->reviewerName = User::find($dataChange->reviewed_by)->full_name;                
        }
        else{
            $dataChange->reviewerName = $dataChange->status == "pending"?"Not yet reviewed":"N/A";
        }
        $compulsoryFields = Student::$compulsoryFields;        
        return view('student.viewDataChangeDetail', [
            'dataChange' => $dataChange,
            'fields' => $compulsoryFields
        ]);
    }

    //Method to get the data change histories of a student
    public function getDataChangeHistories(Request $request, $id){
        $student = Student::find($id);

        $dataChanges = DataChange::where('registration_no', $student->registration_no)
            ->orderBy('created_at', 'asc')
            ->get();

        if($dataChanges->isEmpty()){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'No data change history found for the given registration number.',
                    'status' => false
                ], 404);
            }
        }

        $newCollection = $dataChanges->map(function ($dataChange){
            $dataChange->requesterName = User::find($dataChange->requested_by)->full_name;
            if(!is_null($dataChange->reviewed_by)){
                $dataChange->reviewerName = User::find($dataChange->reviewed_by)->full_name;                
            }
            else{
                $dataChange->reviewerName = $dataChange->status == "pending"?"Not yet reviewed":"N/A";
            }
            return $dataChange;
        });

        if($request->wantsJson()){
            return response()->json([
                'message' => 'Data change history retrieved successfully.',
                'status' => true,
                'dataChanges' => $newCollection
            ]);
        }
        
        $compulsoryFields = Student::$compulsoryFields;        
        unset($compulsoryFields['registration_no']);

        return view('student.getDataChangeHistories', [
            'dataChanges' => $newCollection,
            'student' => $student, //this will be the current student data
            'compulsoryFields' => $compulsoryFields
        ]);
    }

    //Method to reject or cancel data change request
    public function cancelDataChange(Request $request){
        $request->validate([
            'id' => 'required',
            'registration_no' => 'required',
            'reason' => 'required',
        ]);

        $dataChange = DataChange::find($request->post('id'));
        if(empty($dataChange)){
            return response()->json([
                'message' => 'Invalid parameter, no data change exists for the given ID'
            ], 404);
        }

        if(!is_null($dataChange->reviewed_by) && trim($dataChange->reviewed_by) != ""){
            return response()->json([
                'message' => 'This data change request has been already reviewed.'
            ], 403);
        }
        //Getting the mongodb client
        $client = DB::connection('mongodb')->getMongoClient();
        $session = $client->startSession();
        try{ 
            // Start transaction
            $session->startTransaction();
            
            //revert to the earlier status of the student

            $student = Student::where('registration_no', $dataChange->registration_no)->first();
            $student->status = $dataChange->old_student_data['status'];
            $student->save();

            $dataChange->reviewed_by = Auth::user()->_id;
            $dataChange->date_of_review = now();
            $dataChange->status = "cancelled";
            $dataChange->reason_if_cancelled = $request->post('reason');
            $dataChange->save();

            $session->commitTransaction();
            return response()->json([
                'message' => 'You have successfully cancelled the request'
            ]);
        }
        catch(Exception $e){
            $session->abortTransaction();
            return response()->json([
                'message' => 'An error has occured while cancelling the request',
                'error' => $e->getMessage().' Line: '.$e->getLine(),
            ], 500);
        }finally {
            // Optional: End session explicitly.
            $session->endSession();
        }

    }

    //method to verify student's information based on registration no, batch and course
    public function verifyStudentInfo(Request $request){
        $request->validate([
            'registration_no' => 'required',
            'batch' => 'required',
            'course' => 'required',            
        ]);

        $student = Student::where('registration_no', trim($request->registration_no))
        ->where('batch', trim($request->batch))
        ->where('course', trim($request->course))
        ->first();

        if(empty($student)){
            return view('layout.errorMessage',[
                'type' =>'warning',
                'title' => 'Not Found',
                'message' => 'The student\'s record you are searching for is not available in our app.'
            ]);
        }

        return view('student.showStudentDetails',[
            'student' => $student,
            'status' => 'success',
        ]);
    }

}
