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
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required|confirmed|min:8'
        ];
    }

    public function messages(): array {
        return [
            'name.required'      => 'Vui lòng nhập tên người dùng', 
            'name.string'        => 'Tên người dùng không hợp lệ',
            'name.max'           => 'Tên người dùng không được vượt quá :max ký tự',

            'email.required'     => 'Vui lòng nhập email',
            'email.email'        => 'Email không hợp lệ',
            'email.regex'        => 'Email không hợp lệ',
            'email.unique'       => 'Email này đã được đăng ký',

            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'password.min'       => 'Mật khẩu phải có ít nhất :min ký tự',
        ];
    }
}
