<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => ['required','confirmed'],
            'password_confirmation' => 'required',
        ];

    }

    //This will customise any error message
    public function messages(){
        if($this->isMethod('post') === false)
        {
            return [];
        }
        return [
            'email.required' => 'Email is required.'
        ];
    }
}
