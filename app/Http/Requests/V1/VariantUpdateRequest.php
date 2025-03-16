<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class VariantUpdateRequest extends FormRequest
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
        // product_id
        // variant_name
        // sku
        // stock
        // extra_price

        $variant = \App\Models\Variant::whereSku($this->route("sku"))->first();

        return [
            'product_id'   => 'required|exists:products,id',
            'variant_name' => 'required|string|max:255',
            'sku'          => 'required|string|max:50|unique:variants,sku,'.($variant ? $variant->id : null),
            'stock'        => 'required|integer|min:0',
            'extra_price'  => 'required|numeric|min:0',

            'product_images'   => 'required|array',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',

            'variant_values'   => 'required|array',
            'variant_values.*' => 'exists:variant_values,id|distinct',
        ];
    }

    public function messages(): array {
        return [
            'product_id.required'   => 'product_id.required',
            'product_id.exists'     => 'product_id.exists',

            'variant_name.required' => 'variant_name.required',
            'variant_name.string'   => 'variant_name.string',
            'variant_name.max'      => 'variant_name.max',

            'sku.required' => 'sku.required',
            'sku.string'   => 'sku.string',
            'sku.max'      => 'sku.max',
            'sku.unique'   => 'sku.unique',

            'stock.required' => 'stock.required',
            'stock.integer'  => 'stock.integer',
            'stock.min'      => 'stock.min',

            'extra_price.required' => 'extra_price.required',
            'extra_price.numeric'  => 'extra_price.numeric',
            'extra_price.min'      => 'extra_price.min',

            'product_images.*.image' => 'Ảnh sản phẩm không hợp lệ',
            'product_images.*.max'   => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',
            'product_images.*.mimes' => 'Ảnh phải là tệp có định dạng: :values',

            'variant_values.required' => 'Vui lòng chọn thuộc tính biến thể',
            'variant_values.array'    => 'Trường variant_values phải là mảng',

            'variant_values.*.*'        => 'Thuộc tính không hợp lệ',
            'variant_values.*.distinct' => 'Không được chọn thuộc tính trùng nhau',
        ];
    }
}
