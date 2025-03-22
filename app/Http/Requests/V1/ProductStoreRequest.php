<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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

        return [
            'name'        => 'required|string|max:255',
            'categoryId'  => 'required|exists:categories,id',
            'slug'        => 'required|string|unique:products',
            'price'       => 'required|numeric|min:0|max:999999999.99',
            'description' => 'required|string',
            'thumbnail'   => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',

            'productImages'   => 'nullable|array',
            'productImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Vui lòng nhập tên sản phẩm',
            'name.string'          => 'Tên sản phẩm không hợp lệ',
            'name.max'             => 'Tên sản phẩm không được vượt quá :max ký tự',

            'categoryId.required'  => 'Vui lòng chọn danh mục',
            'categoryId.exists'    => 'Danh mục không hợp lệ',

            'slug.required'        => 'Vui lòng nhập slug',
            'slug.unique'          => 'Slug đã tồn tại',

            'price.required'       => 'Vui lòng nhập giá sản phẩm',
            'price.min'            => 'Giá sản phẩm phải lớn hơn :min',
            'price.max'            => 'Giá sản phẩm phải nhỏ hơn :max',
            'price.numeric'        => 'Giá sản phẩm không hợp lệ',

            'description.required' => 'Vui lòng nhập mô tả',
            'description.string'   => 'Mô tả không hợp lệ',

            'thumbnail.required'   => 'Vui lòng tải lên ít nhất 1 file',
            'thumbnail.image'      => 'Ảnh thumbnail không hợp lệ',
            'thumbnail.max'        => 'Vui lòng chọn ảnh có kích thước < :max',
            'thumbnail.mimes'      => 'Ảnh phải là tệp có định dạng: :values',

            'productImages.*.image' => 'Ảnh sản phẩm không hợp lệ',
            'productImages.*.max'   => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',
            'productImages.*.mimes' => 'Ảnh phải là tệp có định dạng: :values',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'category_id' => $this->categoryId,
        ]);
    }
}
