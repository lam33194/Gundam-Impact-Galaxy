<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
        $category = \App\Models\Category::whereSlug($this->route('slug'))->first();

        return [
            'name'      => 'required',
            'slug'      => 'required|unique:categories,slug,'.$category->id ?? null,
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Trường tên là bắt buộc',

            'slug.required' => 'Slug là bắt buộc',
            'slug.unique'   => 'Slug đã tồn tại',
            
            'parent_id'     => 'Danh mục cha không tồn tại',
        ];
    }
}
