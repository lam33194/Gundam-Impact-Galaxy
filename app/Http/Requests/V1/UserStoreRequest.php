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
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => 'required|min:8',
            'phone'    => ['required', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'address'  => 'required|string|max:255',
            'avatar'   => 'nullable|image|mimes:png,jpg,gif,jpeg|max:4096',
            'role'     => 'required|in:user,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Vui lòng nhập tên người dùng',
            'name.string'       => 'Tên người dùng không hợp lệ',
            'name.max'          => 'Tên người dùng không được vượt quá :max ký tự',

            'email.required'    => 'Vui lòng nhập email',
            'email.email'       => 'Email không hợp lệ',
            'email.regex'       => 'Email không hợp lệ',
            'email.unique'      => 'Email đã tồn tại',

            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự',

            'phone.required'    => 'Vui lòng nhập số điện thoại',
            'phone.regex'       => 'Số điện thoại không hợp lệ',

            'address.required'  => 'Vui lòng nhập địa chỉ',
            'address.max'       => 'Địa chỉ không được vượt quá :max ký tự',
            'address.string'    => 'Địa chỉ không hợp lệ',

            'avatar.image'      => 'Avatar phải là file ảnh',
            'avatar.mimes'      => 'Ảnh avatar phải là tệp có định dạng: :values',
            'avatar.max'        => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',

            'role.in'           => 'Vai trò không hợp lệ',
        ];
    }
}
