<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataChangeRequest extends FormRequest
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
        $validationRules = [
            'registration_no' => 'required|exists:students,registration_no',
            'records_to_be_changed' => 'required|array|min:1',
            'reason_of_change' => 'required|string|max:255',
        ];

         // Step 2: Validate each selected field has a new value
        foreach ($this->input('records_to_be_changed', []) as $field) {
            $validationRules[$field] = [
                'required', 
                /* 
                function ($attribute, $value, $fail) {
                    if ($value !== 'expected') {
                        $fail('The '.$attribute.' is invalid.');
                    }
                } */
            ];            
        }

        return $validationRules;
    }

    public function messages()
    {
        return [
            'registration_no.required' => 'Registration number is required.',
            'registration_no.exists' => 'This registration number does not exist.',
            'records_to_be_changed.required' => 'Please select at least one field to change.',
            'reason_of_change.required' => 'Please provide a reason for the change.',
        ];
    }
}
