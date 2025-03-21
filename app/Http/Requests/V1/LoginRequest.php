<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        // name
        // email
        // password
        // phone
        // address
        // avatar
        // role
        return [
            'email'    => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required'
        ];
    }

    public function messages(): array {
        return [ 
            'email.required'    => 'Vui lòng nhập email',
            'email.email'       => 'Email không hợp lệ',
            'email.regex'       => 'Email không hợp lệ',

            'password.required' => 'Vui lòng nhập mật khẩu',
        ];
    }
}
