<?php

namespace App\Http\Requests\V1;

use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentStoreRequest extends FormRequest
{
    use ApiResponse;

    public function authorize()
    {
        // Lấy slug từ route
        $product = Product::whereSlug($this->route('slug'))->first();

        if (!$product) {
            throw new HttpResponseException(
                $this->not_found('Sản phẩm không tồn tại')
            );
        }

        // Kiểm tra xem người dùng có đơn hàng đã giao chứa sản phẩm này không
        return Order::where('user_id', $this->user()->id)
            ->where('status_order', Order::STATUS_ORDER_DELIVERED)
            ->whereHas('orderItems', function ($query) use ($product) {
                // Kiểm tra qua ProductVariant liên kết với Product
                $query->whereHas('variant', function ($variantQuery) use ($product) {
                    $variantQuery->where('product_id', $product->id);
                });
            })
            ->exists();
    }

    public function rules()
    {
        return [
            'content' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'content_or_rating' => 'required_without_all:content,rating',
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
        throw new \Illuminate\Auth\Access\AuthorizationException('Bạn chỉ có thể bình luận sau khi nhận hàng');
    }
}
