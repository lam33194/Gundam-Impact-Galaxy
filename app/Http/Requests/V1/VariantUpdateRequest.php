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
            'productId'    => 'required|exists:products,id',
            'variantName'  => 'required|string|max:255',
            'sku'          => 'required|string|max:50|unique:variants,sku,' . ($variant ? $variant->id : null),
            'stock'        => 'required|integer|min:0',
            'extraPrice'   => 'required|numeric|min:0',

            'productImages'   => 'nullable|array',
            'productImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',

            'variantValues'   => 'nullable|array',
            'variantValues.*' => 'exists:variant_values,id|distinct',
        ];
    }

    public function messages(): array
    {
        return [
            'productId.required'   => 'Vui lòng chọn sản phẩm',
            'productId.exists'     => 'Sản phẩm không hợp lệ',

            'variantName.required' => 'Vui lòng nhập tên biến thể',
            'variantName.string'   => 'Tên biến thể không hợp lệ',
            'variantName.max'      => 'Tên biến thể không được vượt quá :max ký tự',

            'sku.required' => 'Vui lòng nhập mã định danh',
            'sku.string'   => 'Mã định danh không hợp lệ',
            'sku.max'      => 'Mã định danh không được vượt quá :max ký tự',
            'sku.unique'   => 'Mã định danh đã tồn tại',

            'stock.required' => 'Vui lòng nhập số hàng tồn kho',
            'stock.integer'  => 'Số hàng tồn kho không hợp lệ',
            'stock.min'      => 'Số hàng tồn kho phải lớn hơn :min',

            'extraPrice.required' => 'Vui lòng nhập giá bổ sung',
            'extraPrice.numeric'  => 'Giá bổ sung không hợp lệ',
            'extraPrice.min'      => 'Giá bổ sung phải lớn hơn :min',

            'productImages.*.image'  => 'Ảnh sản phẩm không hợp lệ',
            'productImages.*.mimes'  => 'Ảnh phải là tệp có định dạng: :values',
            'productImages.*.max'    => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',

            'variantValues.*.*'        => 'Thuộc tính không hợp lệ',
            'variantValues.*.distinct' => 'Không được chọn thuộc tính trùng nhau',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'product_id'   => $this->productId,
            'variant_name' => $this->variantName,
            'extra_price'  => $this->extraPrice,
        ]);
    }
}
