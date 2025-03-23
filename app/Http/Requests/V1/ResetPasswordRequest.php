<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        return [
            'token'    => 'required',
            'email'    => ['required', 'email', 'exists:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages(): array {
        return [ 
            'token.*'           => 'Token không hợp lệ',

            'email.required'    => 'Vui lòng nhập email',
            'email.*'           => 'Email không hợp lệ',
            // 'email.email'
            // 'email.regex'
            // 'email.exists'

            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.min'       => 'Mật khẩu phải có ít nhất :min ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password_confirmation' => $this->passwordConfirmation,
        ]);
    }
}
