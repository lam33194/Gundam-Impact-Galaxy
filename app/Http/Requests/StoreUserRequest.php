<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "user" => "array|required_array_keys:name,email,password,phone",
            "user.name" => "required|string|max:50",
            "user.email" => "required|email|unique:users,email",
            "user.password" => "required|min:5",
            "user.avatar" => "nullable|image|max:10240",
            "user.phone" => "required|numeric",
            // address
            "address" => "array|required_array_keys:address,city,district,ward",
            "address.address" => "required|string|max:50",
            "address.city" => "required",
            "address.district" => "required",
            "address.ward" => "required"
        ];
    }
}
