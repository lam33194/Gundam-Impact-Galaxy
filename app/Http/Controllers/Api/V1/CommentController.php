<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'user',
    ];

    public function index(Request $request, string $slug)
    {
        $products = Product::whereSlug($slug)->first();

        if(!$products) return $this->not_found('Sản phẩm không tồn tại');

        $comments = $products->comments()->getQuery();

        $this->loadRelations($comments, $request);

        $perPage = $request->query('per_page', 10);

        return response()->json($comments->paginate($perPage)->appends($request->query()));
    }

    public function storeComment(Request $request, string $slug)
    {
        $products = Product::whereSlug($slug)->first();

        if(!$products) return $this->not_found('Sản phẩm không tồn tại');

        $data = $request->validate([
            'content' => 'required|string|max:255',
        ]);
        
        $comment = $products->comments()->create(array_merge(
            $data,
            ['user_id' => $request->user()->id,]
        ));

        return $this->created('Thêm bình luận thành công', $comment);
    }

    public function storeRating(Request $request, string $slug)
    {
        // tìm sản phẩm
        $product = Product::whereSlug($slug)->first();

        if(!$product) return $this->not_found('Sản phẩm không tồn tại');

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        // user đang đăng nhập
        $user = $request->user();

        // kiểm tra user đã đánh giá sản phẩm chưa
        $existingComment = $product->comments()
            ->where('user_id', $user->id)
            ->first();

        // nếu có, update rating
        if ($existingComment) {
            $existingComment->update([
                'rating'  => $data['rating'],
            ]);
    
            return $this->ok('Cập nhật đánh giá sản phẩm thành công', $existingComment);
        }

        $comment = $product->comments()->create(array_merge(
            $data,
            ['user_id' => $user->id,]
        ));

        return $this->created('Đánh giá sản phẩm thành công', $comment);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
