<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCourseRequest extends FormRequest
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
            'course_name' => 'required|string|max:255',
            'short_form' => 'required|string|max:10|unique:courses,short_form',
            'course_in_hindi' => 'required|string|max:255',
            'department_id' => 'required'
        ];
    }
}
