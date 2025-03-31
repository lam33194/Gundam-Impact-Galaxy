<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            "user.email" => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('user')->id)
            ],
            "user.avatar" => "nullable|image|max:10240",
            "user.phone" => "required|numeric",
        ];
    }
}
