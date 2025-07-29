<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(!Auth::check()){
            return false;
        }
        return (Auth::user()->hasPermission("create_user"));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(!$this->isMethod("POST")){
            return [];
        }
        return [
            'contact_no' => ['required', 'regex:/^[0-9]{10}$/'],
            'full_name'  => 'required',
            'email' => 'required|email|unique:users,email',
            'university_id'  => 'required',
            'role_id' => 'required',
            'designation' => 'required',
            'place_of_posting' => 'required',
        ];
    }

    //This will customise any error message
    public function messages(){
        if($this->isMethod('post') === false)
        {
            return [];
        }
        return [
            'full_name.required' => 'Please enter full name of the user.',
            'contact_no.required' => 'Please enter contact (phone) number.',
            'contact_no.regex' => 'Please enter a valid contact (phone) number.',
            'email.unique' => 'Email already taken',
            'university_id.required' => 'Please select a university',
        ];
    }
}
