<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignMenuRequest extends FormRequest
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
        if(!$this->isMethod("post")){
            return [];
        }
        return [
            'role_id' => 'required',
            'allowed_menus' => ['required','array','min:1'],
            'allowed_menus.*' => 'string'
        ];
    }

    public function messages(){
        if($this->isMethod('post') === false)
        {
            return [];
        }
        return [
            'role_id.required' => 'Please select at least one user role.',
            'allowed_menus.required' => 'Please select at least one menu that can be accessed by the role.',            
        ];
    }
}
