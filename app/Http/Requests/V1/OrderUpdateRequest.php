<?php

namespace App\Http\Requests\V1;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'user_name'         => 'required|string|max:255',
            'user_email'        => ['required', 'email', 'exists:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'user_phone'        => ['required', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'user_address'      => 'required|string|max:255',
            'user_note'         => 'nullable|string|max:255',

            'ship_user_name'    => 'nullable|string|max:255',
            'ship_user_email'   => ['nullable', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'ship_user_phone'   => ['nullable', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'ship_user_address' => 'nullable|string|max:255',
            'ship_user_note'    => 'nullable|string|max:255',

            'status_order'      => 'required|in:'.Order::STATUS_ORDER_CANCELED.','.Order::STATUS_ORDER_PENDING,
            'type_payment'      => 'required|in:cod,momo,vnpay',
        ];
    }

    public function messages(): array
    {
        return [           
            'user_name.required'       => 'Vui lòng nhập tên người mua.',
            'user_name.string'         => 'Tên người mua phải là chuỗi ký tự.',
            'user_name.max'            => 'Tên người mua không được vượt quá :max ký tự.',
    
            'user_email.required'      => 'Vui lòng nhập email người mua.',
            'user_email.email'         => 'Email người mua không đúng định dạng.',
            'user_email.max'           => 'Email người mua không được vượt quá :max ký tự.',
            'user_email.exists'        => 'Không có tài khoản với email này trong hệ thống',
    
            'user_phone.required'      => 'Vui lòng nhập số điện thoại người mua.',
            'user_phone.max'           => 'Số điện thoại người mua không được vượt quá :max ký tự.',
            'user_phone.regex'         => 'Số điện thoại không hợp lệ',
    
            'user_address.required'    => 'Vui lòng nhập địa chỉ người mua.',
            'user_address.string'      => 'Địa chỉ người mua phải là chuỗi ký tự.',
            'user_address.max'         => 'Địa chỉ người mua không được vượt quá :max ký tự.',
    
            'user_note.string'         => 'Ghi chú người mua phải là chuỗi ký tự.',
            'user_note.max'            => 'Ghi chú người mua không được vượt quá :max ký tự.',
    
            'ship_user_name.string'    => 'Tên người nhận phải là chuỗi ký tự.',
            'ship_user_name.max'       => 'Tên người nhận không được vượt quá :max ký tự.',
    
            'ship_user_email.email'    => 'Email người nhận không đúng định dạng.',
            'ship_user_email.max'      => 'Email người nhận không được vượt quá :max ký tự.',
    
            'ship_user_phone.max'      => 'Số điện thoại người nhận không được vượt quá :max ký tự.',
            'ship_user_phone.regex'    => 'Số điện thoại người nhận không hợp lệ.',
    
            'ship_user_address.string' => 'Địa chỉ người nhận phải là chuỗi ký tự.',
            'ship_user_address.max'    => 'Địa chỉ người nhận không được vượt quá :max ký tự.',
    
            'ship_user_note.string'    => 'Ghi chú người nhận phải là chuỗi ký tự.',
            'ship_user_note.max'       => 'Ghi chú người nhận không được vượt quá :max ký tự.',

            'type_payment.required'    => 'Phương thức thanh toán không được để trông.',
            'type_payment.in'          => 'Phương thức thanh toán không hợp lệ.',
            'status_order.in'          => 'Trạng thái đơn hàng không hợp lệ.',
        ];
    }
}
