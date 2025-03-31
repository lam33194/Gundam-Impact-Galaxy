<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use ApiRequestJsonTrait;
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
            'email'     => 'required|email',
            'password'  => 'required|string|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email là bắt buộc.',
            'email.email'    => 'Email phải là một địa chỉ email hợp lệ.',
            'email.unique'   => 'Email đã tồn tại. Vui lòng chọn một email khác.',  // Thông báo lỗi cho email trùng

            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string'   => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min'      => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ];
    }
}
