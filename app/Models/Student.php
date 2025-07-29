<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\CustomLibrary\JWSExtractor;

class Student extends Model
{
    protected $table = "students";
    protected $connection = 'mongodb'; // Use MongoDB connection
    //protected $collection = 'students'; // Ensure this matches your MongoDB collection
    protected $guarded = []; // Allow mass assignment of any field (since fields are dynamic)
    
    //These below are the fields that are mandatorily requied in any record of a student
    public static $compulsoryFields = [
        'sl_no' => ['DisplayAs' => 'Sl. No.'],
        'registration_no' => ['DisplayAs' => 'Registration Number'],
        'name_of_students' => [
            'DisplayAs' => 'Students Name',
            'isGroup' => true,
            'subFields' => [
                'name_of_students_in_hindi' => ['DisplayAs' => 'छात्रों_का_नाम']
            ]
        ],

        'name_of_students_in_hindi' => ['DisplayAs' => 'छात्रों_का_नाम'],
        'course' => [
            'DisplayAs' => 'Course',
            'isGroup' => true,
            'subFields' => [
                'course_in_hindi' => ['DisplayAs' => 'पाठ्यक्रम']
            ]
        ],

        'course_in_hindi' => ['DisplayAs' => 'पाठ्यक्रम'],
        'batch' => ['DisplayAs' => 'Batch'],
        'year' => ['DisplayAs' => 'Year of Passing'],

        'department' => [
            'DisplayAs' => 'Department',
            'isGroup' => true,
            'subFields' => [
                'department_in_hindi' => ['DisplayAs' => 'विभाग']
            ]
        ],


        'department_in_hindi' => ['DisplayAs' => 'विभाग'],
        'month' => [
            'DisplayAs' => 'Month',
            'isGroup' => true,
            'subFields' => [
                'month_in_hindi' => ['DisplayAs' => 'महीना']
            ]
        ],

        'month_in_hindi' => ['DisplayAs' => 'महीना'],
        'gender' => ['DisplayAs' => 'Gender'],
        'father_name' => ['DisplayAs' => 'Father Name'],
        'mother_name' => ['DisplayAs' => 'Mother Name'],
        'sports' => [
            'DisplayAs' => 'Sports',
            'isGroup' => true,
            'subFields' => [
                'sports_in_hindi' => ['DisplayAs' => 'खेल']
            ]
        ],

        'sports_in_hindi' => ['DisplayAs' => 'खेल'],
        
        'grade' => ['DisplayAs' => 'Grade'],
    ];    
    /*  
    public static $compulsoryFields = [
        'sl_no' => ['DisplayAs' => 'Sl. No.'],
        'registration_no' => ['DisplayAs' => 'Registration Number'],
        'name_of_students' => ['DisplayAs' => 'Students Name'],
        'name_of_students_in_hindi' => ['DisplayAs' => 'छात्रों_का_नाम'],
        'course' => ['DisplayAs' => 'Course'],
        'course_in_hindi' => ['DisplayAs' => 'पाठ्यक्रम'],
        'batch' => ['DisplayAs' => 'Batch'],
        'year' => ['DisplayAs' => 'Year of Passing'],
        'department' => [ 'DisplayAs' => 'Department'],
        'department_in_hindi' => ['DisplayAs' => 'विभाग'],
        'month' => [ 'DisplayAs' => 'Month'],
        'month_in_hindi' => ['DisplayAs' => 'महीना'],
        'gender' => ['DisplayAs' => 'Gender'],
        'father_name' => ['DisplayAs' => 'Father Name'],
        'mother_name' => ['DisplayAs' => 'Mother Name'],
        'sports' => [ 'DisplayAs' => 'Sports'],
        'sports_in_hindi' => ['DisplayAs' => 'खेल'],        
        'grade' => ['DisplayAs' => 'Grade'],
    ]; */ 
    
    // Method to check if a student information has been approved and esigned or not
    public function isSigned(): bool{
        if($this->status == 0){
            return false;
        }
        if(!isset($this->approved_by['jws'])){
            return false;
        }
        return true;
    }
  
    // Method to check if the signature is valid or not
    public function isSignatureValid():bool
    {
        if(!isset($this->approved_by['jws'])){
            return false;
        }
        $jws = $this->approved_by['jws'];

        // Here is the logic to validate the JWS signature
        return JWSExtractor::isValidJWS($jws);
    }

    //Method to verify the integrity of the signed data with the JWS
    public function verifySignedData():bool
    {
        if(!isset($this->approved_by['jws'])){
            return false;
        }
        $jws = $this->approved_by['jws'];  

        // Here is the logic to verify the integrity of the signed data
        return JWSExtractor::verifySignedData($jws, $this->getRawData());
    }

    // Method to get the Signer name
    public function getSignerName():string
    {
        // Check if the student information is signed
        if(!$this->isSigned()){
            return 'Not signed yet';
        }
        // If the document is not signed or JWS is not set, return a default message
        if(!isset($this->approved_by['jws'])){
            return 'No Signature Found';
        }

        $jws = $this->approved_by['jws'];
        return JWSExtractor::getSignerName($jws);
    }

    // Method to get the jws
    public function getJWS():?string
    {
        // Check if the student information is signed
        if(!$this->isSigned()){
            return null;
        }
        // If the document is not signed or JWS is not set, return null
        if(!isset($this->approved_by['jws'])){
            return null;
        }

        return $this->approved_by['jws'];
    }

    public function isSameAsSignedData($key){
        $payload = JWSExtractor::decodePayload($this->approved_by['jws']);
        if(!isset($payload[$key])){
            if(!isset($this->{$key})){
                return true;
            }
            return false;
        }
        return(trim($this->{$key}) === trim($payload[$key]));        
    }

    public function getSignedData($key){
        $payload = JWSExtractor::decodePayload($this->approved_by['jws']);
        if(!isset($payload[$key])){
            return 'N/A';
        }
        return $payload[$key];
    }

    public function getAllSignedData(){
        if(!$this->isSigned()){
            return null;
        }
        $data = JWSExtractor::decodePayload($this->approved_by['jws']);
        return $data;
    }

    //This method will return Data to verify against the JWS
    public function getRawData(){
        $data = $this->toArray();
        // Exclude fields that are not part of the signed data
        unset($data['approved_by']); // Exclude the 'approved_by' from the data
        unset($data['updated_at']); // Exclude 'updated_at' if it exists, as it may change over time
        unset($data['status']); // Exclude 'approved' if it exists, as it may change over time
        return $data;
    }
}
