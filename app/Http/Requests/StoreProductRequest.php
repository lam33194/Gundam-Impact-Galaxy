<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            // products
            'product.*' => 'required',
            'product.name' => 'required|string|max:255',
            'product.slug' => 'nullable|unique:products,slug|max:255',
            'product.sku' => 'required|unique:products,sku|max:255',
            'product.thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp',
            'product.price_regular' => 'required|numeric',
            'product.price_sale' => 'nullable|numeric|lt:product.price_regular',
            'product.description' => 'nullable',
            'product.content' => 'nullable',
            // product_galleries
            'product_galleries' => 'required|array|min:1|max:5',
            'product_galleries.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp',
            // colors
            'colors' => "required|array|min:1",
            // sizes
            'sizes' => "required|array|min:1",
            // tags
            'tags' => "required|array|min:1"

        ];
    }
}
