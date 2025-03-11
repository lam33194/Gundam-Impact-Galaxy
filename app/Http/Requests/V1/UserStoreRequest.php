<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
        // email_verified_at
        // password
        // remember_token
        // phone
        // address
        // avatar
        // role

        return [
            'name'     => 'required | string | max:255',
            'email'    => 'required | string | max:255 | email | unique:users,email',
            'password' => 'required | min:8',
            'phone'    => 'required | string | max:16',
            'address'  => 'required | string | max:255',
            'avatar'   => 'nullable | image  | mimes:png,jpg,gif,jpeg | max:4096',
            'role'     => 'required | in:user,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Trường tên là bắt buộc',
            'name.max'          => 'name.max',

            'email.required'    => 'Trường email là bắt buộc',
            'email.email'       => 'Email không hợp lệ',
            'email.unique'      => 'Email đã tồn tại',

            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min'      => 'Mật khẩu phải có ít nhất 8 ký tự',

            'phone.required'    => 'Vui lòng nhập số điện thoại',
            'phone.max'         => 'Số điện thoại không được vượt quá 16 ký tự',
            // numeric
            // unique
            // digits:10
            // digits_between:9,11
            // regex:/^0[0-9]{9}$/
                // ^0: Bắt đầu bằng số 0.
                // [0-9]{9}: Theo sau là 9 chữ số bất kỳ.
                // $: Kết thúc chuỗi.
            
            'address.required'  => 'Vui lòng nhập địa chỉ',
            'address.max'       => 'add.max',
            
            'avatar.image'      => 'Avatar phải là file ảnh',
            'avatar.mimes'      => 'avatar.mimes',
            'avatar.max'        => 'Avatar không được vượt quá 10MB',

            'role.in'           => 'Vai trò không hợp lệ',
        ];
    }
}
