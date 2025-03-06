<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name'     => 'required | string | max:255',
            'phone'    => 'required | string | max:16',
            'address'  => 'required | string | max:255',
            'avatar'   => 'nullable | image  | mimes:png,jpg,gif,jpeg | max:10240',
            'role'     => 'required | in:user,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Trường tên là bắt buộc',
            'name.max'          => 'Tên không được vượt quá 255 ký tự',

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
