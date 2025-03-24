<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CartItemStoreRequest extends FormRequest
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
        // id
        // user_id
        // product_id
        // variant_id
        // variant_name
        // sku
        // product_price
        // extra_price
        // quantity
        // attributes

        return [
            'productId'  => 'required|exists:products,id',
            'variantId'  => 'required|exists:variants,id',
            'quantity'   => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'productId.required' => 'Vui điền vào trường :attribute',
            'productId.exists'   => 'Sản phẩm không tồn tại',

            'variantId.required' => 'Vui điền vào trường :attribute',
            'variantId.exists'   => 'Biến thể không tồn tại',

            'quantity.required'  => 'Vui lòng chọn số lượng sản phẩm',
            'quantity.integer'   => 'Số lượng sản phẩm không hợp lệ',
            'quantity.min'       => 'Vui lòng chọn ít nhất :min số lượng sản phẩm',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'product_id'    => $this->productId,
            'variant_id'    => $this->variantId,
        ]);
    }
}
