<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
        // slug
        // parent_id

        return [
            'name'      => 'required|string|max:255',
            'slug'      => 'required|unique:categories',
            'parentId'  => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên danh mục',

            'slug.required' => 'Vui lòng nhập slug',
            'slug.unique'   => 'Slug đã tồn tại',

            'parentId'      => 'Danh mục cha không tồn tại',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'parent_id' => $this->parentId,
        ]);
    }
}
