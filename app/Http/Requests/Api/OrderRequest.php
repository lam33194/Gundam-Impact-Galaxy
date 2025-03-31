<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'user_id'           => 'required|integer|exists:users,id',
            'user_name'         => 'required|string|max:255',
            'user_email'        => 'required|email|max:255',
            'user_phone'        => 'required|max:255',
            'user_address'      => 'required|string|max:255',
            'user_note'         => 'nullable|string|max:255',
            'ship_user_name'    => 'nullable|string|max:255',
            'ship_user_email'   => 'nullable|email|max:255',
            'ship_user_phone'   => 'nullable|max:255',
            'ship_user_address' => 'nullable|string|max:255',
            'ship_user_note'    => 'nullable|string|max:255',
            'status_order'      => 'required|in:pending,confirmed,preparing_goods,shipping,delivered,canceled',
            'status_payment'    => 'required|in:unpaid,paid,failed',
            'type_payment'      => 'required|in:vnpay,momo,cod',
            'total_price'       => 'required|min:0',
            'voucher_code'      => 'nullable'
        ];
    }
    


    public function messages(): array
    {
        return [
            'user_id.required'           => 'Vui lòng chọn người dùng.',
            'user_id.integer'            => 'ID người dùng không hợp lệ.',
            'user_id.exists'             => 'Người dùng không tồn tại trong hệ thống.',
    
            'user_name.required'         => 'Vui lòng nhập tên người mua.',
            'user_name.string'           => 'Tên người mua phải là chuỗi ký tự.',
            'user_name.max'              => 'Tên người mua không được vượt quá 255 ký tự.',
    
            'user_email.required'        => 'Vui lòng nhập email người mua.',
            'user_email.email'           => 'Email người mua không đúng định dạng.',
            'user_email.max'             => 'Email người mua không được vượt quá 255 ký tự.',
    
            'user_phone.required'        => 'Vui lòng nhập số điện thoại người mua.',
            'user_phone.max'             => 'Số điện thoại người mua không được vượt quá 255 ký tự.',
    
            'user_address.required'      => 'Vui lòng nhập địa chỉ người mua.',
            'user_address.string'        => 'Địa chỉ người mua phải là chuỗi ký tự.',
            'user_address.max'           => 'Địa chỉ người mua không được vượt quá 255 ký tự.',
    
            'user_note.string'           => 'Ghi chú người mua phải là chuỗi ký tự.',
            'user_note.max'              => 'Ghi chú người mua không được vượt quá 255 ký tự.',
    
            'ship_user_name.string'      => 'Tên người nhận phải là chuỗi ký tự.',
            'ship_user_name.max'         => 'Tên người nhận không được vượt quá 255 ký tự.',
    
            'ship_user_email.email'      => 'Email người nhận không đúng định dạng.',
            'ship_user_email.max'        => 'Email người nhận không được vượt quá 255 ký tự.',
    
            'ship_user_phone.max'        => 'Số điện thoại người nhận không được vượt quá 255 ký tự.',
    
            'ship_user_address.string'   => 'Địa chỉ người nhận phải là chuỗi ký tự.',
            'ship_user_address.max'      => 'Địa chỉ người nhận không được vượt quá 255 ký tự.',
    
            'ship_user_note.string'      => 'Ghi chú người nhận phải là chuỗi ký tự.',
            'ship_user_note.max'         => 'Ghi chú người nhận không được vượt quá 255 ký tự.',
    
          
            'status_order.in'            => 'Trạng thái đơn hàng không hợp lệ.',
            'status_payment.in'          => 'Trạng thái thanh toán không hợp lệ.',
            'type_payment.required'      => 'Loại thanh toán không được để trông.',
            'type_payment.in'            => 'Loại thanh toán không hợp lệ.',
    
            'total_price.required'       => 'Vui lòng nhập tổng giá trị đơn hàng.',
            'total_price.min'            => 'Tổng giá trị đơn hàng không được âm.',
        ];
    }
    
}
