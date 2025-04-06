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
            'name'     => 'required|string|max:255',
            'phone'    => ['required', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'avatar'   => 'nullable|image|mimes:png,jpg,gif,jpeg|max:10240',

            // addresses
            'address'    => 'required|string|max:255',
            'ward'       => 'required|string|max:255',
            'district'   => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            // 'is_primary' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Vui lòng nhập tên người dùng',
            'name.string'       => 'Tên người dùng không hợp lệ',
            'name.max'          => 'Tên người dùng không được vượt quá :max ký tự',

            'phone.required'    => 'Vui lòng nhập số điện thoại',
            'phone.regex'       => 'Số điện thoại không hợp lệ',

            'avatar.image'      => 'Avatar phải là file ảnh',
            'avatar.mimes'      => 'Ảnh avatar phải là tệp có định dạng: :values',
            'avatar.max'        => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',

            // 'address.required'  => 'Vui lòng nhập địa chỉ',
            // 'address.max'       => 'Địa chỉ không được vượt quá :max ký tự',
            // 'address.string'    => 'Địa chỉ không hợp lệ',

            // 'ward.required'     => 'Vui lòng nhập địa chỉ',
            // 'ward.max'          => 'Địa chỉ không được vượt quá :max ký tự',
            // 'ward.string'       => 'Địa chỉ không hợp lệ',

            // 'district.required'  => 'Vui lòng nhập địa chỉ',
            // 'district.max'       => 'Địa chỉ không được vượt quá :max ký tự',
            // 'district.string'    => 'Địa chỉ không hợp lệ',

            // 'city.required'      => 'Vui lòng nhập địa chỉ',
            // 'city.max'           => 'Địa chỉ không được vượt quá :max ký tự',
            // 'city.string'        => 'Địa chỉ không hợp lệ',
        ];
    }
}
