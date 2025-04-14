<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CommentStoreRequest;
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

    public function store(CommentStoreRequest $request, string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        $data = $request->validated();
        
        if ($product->comments()->where('user_id', $request->user()->id)->exists()) {
            return $this->failedValidation('Bạn đã bình luận sản phẩm này');
        }

        // Tạo bình luận mới
        $comment = $product->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content'] ?? null,
            'rating'  => $data['rating'] ?? null,
        ]);

        $this->loadRelations($comment, $request, true);

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
