<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address'    => 'nullable|string|max:255',
            'ward'       => 'nullable|string|max:255',
            'district'   => 'nullable|string|max:255',
            'city'       => 'nullable|string|max:255',
            'is_primary' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'address.required'  => 'Vui lòng nhập địa chỉ',
            'address.max'       => 'Địa chỉ không được vượt quá :max ký tự',
            'address.string'    => 'Địa chỉ không hợp lệ',

            'ward.required'     => 'Vui lòng chọn Phường / Xã',
            'ward.max'          => 'Phường / Xã không được vượt quá :max ký tự',
            'ward.string'       => 'Phường / Xã không hợp lệ',

            'district.required'  => 'Vui lòng chọn Quận / Huyện',
            'district.max'       => 'Quận / Huyện không được vượt quá :max ký tự',
            'district.string'    => 'Quận / Huyện không hợp lệ',

            'city.required'      => 'Vui lòng chọn Thành phố',
            'city.max'           => 'Thành phố không được vượt quá :max ký tự',
            'city.string'        => 'Thành phố không hợp lệ',
        ];
    }
}
