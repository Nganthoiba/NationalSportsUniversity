<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(!$this->isMethod('post')) {
            // If the request is a POST request, we can add additional rules or modify existing ones
            // For example, we can add a unique rule for the name_of_students field
            return [];
        }

        return [
            'name_of_students' => 'required|string|max:255',
            'name_of_students_in_hindi' => 'required|string|max:255',
            'month' => 'required|string|regex:/^[a-zA-Z]+~[^\x00-\x7F]+$/', // Format: English~Hindi
            'year' => 'required|integer|min:1900|max:2100',
            // batch should be inthe format 'YYYY-YY'accepted
            'batch' => 'required|string|regex:/^\d{4}-\d{2}$/', 
            'course' => 'required|exists:courses,id',
            'department' => 'required|exists:departments,id',
            'sports' => 'required|exists:sports,id',
            'gender' => 'required|string',
            'grade' => 'string|max:10',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
        ];
    }

    //Validation messages
    public function messages(): array
    {
        return [
            'name_of_students.required' => 'The name of students is required.',
            'name_of_students_in_hindi.required' => 'The name of students in Hindi is required.',
            'month.required' => 'The month is required.',
            'month.regex' => 'The month must be in the format English~Hindi (e.g., January~जनवरी).',
            'year.required' => 'The year is required.',
            'batch.required' => 'The batch is required.',
            'batch.regex' => 'The batch must be in the format YYYY-YY.',
            'course.required' => 'The course is required.',
            'course.exists' => 'The selected course does not exist.',
            'department.required' => 'The department is required.',
            'department.exists' => 'The selected department does not exist.',
            'sports.required' => 'The sports is required.',
            'sports.exists' => 'The selected sports does not exist.',
        ];
    }
}
