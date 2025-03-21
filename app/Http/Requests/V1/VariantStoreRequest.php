<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class VariantStoreRequest extends FormRequest
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
        return [
            'product_id'   => 'required|exists:products,id',
            'variant_name' => 'required|string|max:255',
            'sku'          => 'required|string|max:50|unique:variants,sku',
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
            'product_id.required'   => 'Vui lòng chọn sản phẩm',
            'product_id.exists'     => 'Sản phẩm không hợp lệ',

            'variant_name.required' => 'Vui lòng nhập tên biến thể',
            'variant_name.string'   => 'Tên biến thể không hợp lệ',
            'variant_name.max'      => 'Tên biến thể không được vượt quá :max ký tự',

            'sku.required' => 'Vui lòng nhập mã định danh',
            'sku.string'   => 'Mã định danh không hợp lệ',
            'sku.max'      => 'Mã định danh không được vượt quá :max ký tự',
            'sku.unique'   => 'Mã định danh đã tồn tại',

            'stock.required' => 'Vui lòng nhập số hàng tồn kho',
            'stock.integer'  => 'Số hàng tồn kho không hợp lệ',
            'stock.min'      => 'Số hàng tồn kho phải lớn hơn :min',

            'extra_price.required' => 'Vui lòng nhập giá bổ sung',
            'extra_price.numeric'  => 'Giá bổ sung không hợp lệ',
            'extra_price.min'      => 'Giá bổ sung phải lớn hơn :min',

            'product_images.required' => 'Vui lòng tải lên ít nhất 1 ảnh biến thể',
            'product_images.*.image'  => 'Ảnh sản phẩm không hợp lệ',
            'product_images.*.mimes'  => 'Ảnh phải là tệp có định dạng: :values',
            'product_images.*.max'    => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',

            'variant_values.required'   => 'Vui lòng chọn ít nhất 1 thuộc tính biến thể',
            'variant_values.*.*'        => 'Thuộc tính không hợp lệ',
            'variant_values.*.distinct' => 'Không được chọn thuộc tính trùng nhau',
        ];
    }
}
