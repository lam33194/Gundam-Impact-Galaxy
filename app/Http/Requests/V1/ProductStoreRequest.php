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
            'name'        => 'required | string | max:255',
            'category_id' => 'required | exists:categories,id',
            'slug'        => 'required | string | unique:products',
            'price'       => 'required | numeric | min:0 | max:999999999.99',
            'thumbnail'   => 'required | array',
            'thumbnail.*' => 'image | mimes:jpeg,png,jpg,gif | max:max:4096',
            'description' => 'required | string',
        ];
    }
}
