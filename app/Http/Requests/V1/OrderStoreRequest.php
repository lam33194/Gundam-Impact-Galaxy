<?php

namespace App\Http\Requests\V1;

use App\Models\Voucher;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderStoreRequest extends FormRequest
{
    use ApiResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $cartItems = auth('sanctum')->user()->cartItems()->get()->toArray();

        return !empty($cartItems);
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
            'user_email'        => ['required', 'email', 'exists:users,email', 'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'user_phone'        => ['required', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'user_address'      => 'required|string|max:255',
            'user_note'         => 'nullable|string|max:255',

            'ship_user_name'    => 'nullable|string|max:255',
            'ship_user_email'   => ['nullable', 'email', 'regex:/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'ship_user_phone'   => ['nullable', 'string', 'regex:/^0[0-9]{9,10}$/'],
            'ship_user_address' => 'nullable|string|max:255',
            'ship_user_note'    => 'nullable|string|max:255',

            'type_payment'      => 'required|in:cod,vnpay',
            'voucher_code'      => 'nullable|exists:vouchers,code'
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
          
            // 'status_order.in'          => 'Trạng thái đơn hàng không hợp lệ.',
            // 'status_payment.in'        => 'Trạng thái thanh toán không hợp lệ.',
            'type_payment.required'    => 'Phương thức thanh toán không được để trông.',
            'type_payment.in'          => 'Phương thức thanh toán không hợp lệ.',
    
            // 'total_price.required'     => 'Vui lòng nhập tổng giá trị đơn hàng.',
            // 'total_price.min'          => 'Tổng giá trị đơn hàng không được âm.',
        ];
    }
    
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->failed_validation('Giỏ hàng của bạn đang trống')
        );
    }

    protected function passedValidation()
    {
        $voucherCode = $this->input('voucher_code');

        if ($voucherCode) {
            /** @var \App\Models\User $user */
            $totalPrice = auth('sanctum')->user()->total_price;

            $voucher = Voucher::where('code', $voucherCode)->first();

            // Kiểm tra trạng thái hoạt động (is_active)
            if (!$voucher->is_active) throw new HttpResponseException(
                $this->error("Voucher này hiện không hoạt động")
            );

            // Kiểm tra thời gian hiệu lực (start_date_time & end_date_time)
            $now = now();
            if ($now > $voucher->end_date_time) throw new HttpResponseException(
                $this->error("Voucher đã hết hiệu lực")
            );

            if ($now < $voucher->start_date_time) throw new HttpResponseException(
                $this->error("Voucher này chưa có hiệu lực")
            );

            // Kiểm tra giá trị tối thiểu (min_order_amount)
            if ($totalPrice < $voucher->min_order_amount) throw new HttpResponseException(
                $this->error("Để sử dụng voucher này, đơn hàng của bạn phải có tổng giá ít nhất " . 
                number_format($voucher->min_order_amount, 0, ',', '.') . ' VNĐ')
            );

            // Kiểm tra số lần sử dụng tối đa
            if ($voucher->max_usage !== null && $voucher->used_count >= $voucher->max_usage) throw new HttpResponseException(
                $this->error("Voucher này đã được sử dụng hết số lần cho phép")
            );
        }
    }
}
