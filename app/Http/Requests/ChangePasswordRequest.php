<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => ['required', 'current_password:web'], // Validates the current password
            'new_password' => ['required', 'min:8', 'different:current_password'], // Must be at least 8 characters and different from the current password
            'confirm_new_password' => ['required', 'same:new_password'], // Must match the new password
        ];
    }

    //This will customise any error message
    public function messages(){
        if($this->isMethod('post') === false)
        {
            return [];
        }
        return [
            'current_password.current_password' => 'The current password you have entered is incorrect.',
            'confirm_new_password.same' => 'The confirmation password must match the new password.'
        ];
    }
}
