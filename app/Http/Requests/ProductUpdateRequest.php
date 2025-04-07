<?php

namespace App\Http\Requests;

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
        return [
            'product.name' => 'required|string|max:255',
            'product.sku' => 'required|string|max:255|unique:products,sku,' . $this->route('product')->id,
            'product.price_regular' => 'required|numeric|min:0',
            'product.price_sale' => 'nullable|numeric|min:0',
            'product.description' => 'nullable|string',
            'product.content' => 'nullable|string',
            'product.category_id' => 'required|exists:categories,id',
            'product.thumb_image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:product_colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:product_sizes,id',
            'product_variants' => 'nullable|array',
            'product_variants.*.quantity' => 'required|integer|min:0',
            'product_variants.*.image' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'product_galleries' => 'nullable|array',
            'product_galleries.*' => 'image|mimes:jpeg,png,gif|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
