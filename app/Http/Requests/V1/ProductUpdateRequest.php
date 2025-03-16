<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        // name
        // category_id
        // slug
        // price
        // thumbnail
        // description

        $product = \App\Models\Product::whereSlug($this->route('slug'))->first();

        return [
            'name'        => 'required | string | max:255',
            'category_id' => 'required | exists:categories,id',
            'slug'        => 'required | string | unique:products,slug,' . ($product ? $product->id : null),
            'price'       => 'required | numeric | min:0 | max:999999999.99',
            'description' => 'required | string',
            'thumbnail'   => 'nullable | image | mimes:jpeg,png,jpg,gif | max:4096',

            'product_images'   => 'nullable | array',
            'product_images.*' => 'image | mimes:jpeg,png,jpg,gif | max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Trường tên là bắt buộc',
            'name.string'          => 'Tên không hợp lệ',
            'name.max'             => 'Tên quá dài',

            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists'   => 'Danh mục không hợp lệ',

            'slug.required'        => 'Trường slug là bắt buộc',
            'slug.unique'          => 'Slug đã tồn tại',

            'price.*'              => 'Giá không hợp lệ',

            'description.required' => 'Vui lòng nhập mô tả',
            'description.string'   => 'Mô tả không hợp lệ',

            'thumbnail.image'      => 'Ảnh thumbnail không hợp lệ',
            'thumbnail.max'        => 'Vui lòng chọn ảnh có kích thước < :max',
            'thumbnail.mimes'      => 'Ảnh phải là tệp có định dạng: :values',

            'product_images.*.image' => 'Ảnh sản phẩm không hợp lệ',
            'product_images.*.max'   => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',
            'product_images.*.mimes' => 'Ảnh phải là tệp có định dạng: :values',
        ];
    }
}
