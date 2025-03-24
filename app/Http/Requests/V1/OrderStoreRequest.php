<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
        // user_id
        // order_code
        // total_amount
        // status
        // shipping_address
        // shipping_fee
        // note

        return [
            'shippingAddress' => 'required|string',
            'shippingFee'     => 'nullable|numeric|min:0',
            'note'            => 'nullable|string',
        ];
        // 'orderCode'       => 'required|string|max:20|unique:orders,order_code',
        // 'totalAmount'     => 'required|numeric|min:0',
        // 'status'          => 'in:pending,processing,shipped,delivered,cancelled',
        // 'shipping_fee' => 'sometimes|numeric|min:0|max:999999999.99',
        // 'note' => 'nullable|string',
    }

    public function messages(): array
    {
        return [
            // 'userId.requried'          =>  '',
            // 'orderCode.requried'       =>  '',
            // 'totalAmount.requried'     =>  '',
            // 'status.requried'          =>  '',
            // 'shippingAddress.requried' =>  '',
            // 'shippingFee.requried'     =>  '',
            // 'note.requried'            =>  '',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'shipping_address' => $this->shippingAddress,
            'shipping_fee'     => $this->shippingFee,
        ]);
    }
}
