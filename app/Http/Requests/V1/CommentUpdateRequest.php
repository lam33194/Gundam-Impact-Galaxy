<?php

namespace App\Http\Requests\V1;

use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentUpdateRequest extends FormRequest
{
    use ApiResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $product = Product::whereSlug($this->route('slug'))->first();

        if (!$product) {
            throw new HttpResponseException(
                $this->not_found('Sản phẩm không tồn tại')
            );
        }

        $comment = $product->comments()->find($this->route('id'));

        if (!$comment) {
            throw new HttpResponseException(
                $this->not_found('Bình luận không tồn tại')
            );
        }

        return $comment->user_id == auth('sanctum')->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'content' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'content_or_rating' => 'required_without_all:content,rating',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'content_or_rating.required_without_all' => 'Vui lòng nhập nội dung bình luận hoặc đánh giá',
        ];
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->forbidden('Bạn không có quyền chỉnh sửa bình luận này')
        );
    }
}
