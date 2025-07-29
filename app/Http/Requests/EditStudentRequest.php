<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditStudentRequest extends FormRequest
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
        if($this->isMethod("post") === false)
        {
            return [
            //
            ];
        }

        return [
            'student_id' => 'required',
            'name_of_students' => 'required',
            'name_of_students_in_hindi' => 'required',
            'course' => 'required',
            'course_in_hindi' => 'required',
            'batch' => 'required',
            'year' => 'required',
            'department' => 'required',
            'department_in_hindi' => 'required',
            'month' => 'required',
            'month_in_hindi' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'sports' => 'nullable',
            'sports_in_hindi' => 'nullable',
            'grade' => 'required',
        ];
    }
}
