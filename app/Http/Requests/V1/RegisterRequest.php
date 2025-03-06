<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'      => 'required|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|confirmed|min:8'
        ];
    }

    public function messages(): array {
        return [
            'name.required'     => 'Trường tên là bắt buộc', 

            'email.required'    => 'Trường email là bắt buộc',
            'email.email'       => 'Email không hợp lệ',  
            'email.unique'      => 'Email đã được sử dụng',

            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.min'       => 'Mật khẩu phải có ít nhất 8 kí tự',
        ];
    }
}
