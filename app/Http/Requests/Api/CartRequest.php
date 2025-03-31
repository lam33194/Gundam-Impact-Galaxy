<?php

namespace App\Http\Requests\Api;

use App\Traits\ApiRequestJsonTrait;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    use ApiRequestJsonTrait;

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
        if ($this->isMethod('post')) {
            return $this->rulesForCreate();
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate();
        }

        return [];
    }
    public function rulesForCreate()
    {
        return [
            'user_id'               => 'required|integer|exists:users,id',
            'product_variant_id'    => 'required|integer|exists:product_variants,id',
            'quantity'              => 'required|integer|min:1'
        ];
    }

    public function rulesForUpdate()
    {
        return [
            'quantity'              => 'required|integer|min:1'
        ];
    }

    // messages chung
    public function messages()
    {
        return [
            'user_id.required' => 'Người dùng là bắt buộc.',
            'user_id.integer' => 'Người dùng không hợp lệ.',
            'user_id.exists' => 'Người dùng không tồn tại trong hệ thống.',

            'product_variant_id.required' => 'Sản phẩm là bắt buộc.',
            'product_variant_id.integer' => 'Sản phẩm không hợp lệ.',
            'product_variant_id.exists' => 'Sản phẩm không tồn tại trong hệ thống.',

            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là một số nguyên.',
            'quantity.min' => 'Số lượng tối thiểu là 1.'
        ];
    }
}
