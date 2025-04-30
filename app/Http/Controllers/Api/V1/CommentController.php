<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CommentStoreRequest;
use App\Http\Requests\V1\CommentUpdateRequest;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\StorageFile;
// use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse, StorageFile;

    // protected $validRelations = [
    //     'user',
    // ];

    public function index(Request $request, string $slug)
    {
        $products = Product::whereSlug($slug)->first();

        if (!$products) return $this->not_found('Sản phẩm không tồn tại');

        $comments = $products->comments()->with([
            'user:id,name,email,avatar',
            'commentImages:id,comment_id,image',
        ])->getQuery();

        $perPage = $request->query('per_page', 10);

        return response()->json($comments->paginate($perPage)->appends($request->query()));
    }

    public function getUserComments(Request $request)
    {
        $user = auth('sanctum')->user();

        $comments = $user->comments()->with([
            'product:id,name,slug,sku,thumb_image,price_regular,price_sale',
            'commentImages:id,comment_id,image'
        ])->getQuery();

        $perPage = $request->query('per_page', 10);

        return response()->json($comments->paginate($perPage)->appends($request->query()));
    }

    public function store(CommentStoreRequest $request, string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        $data = $request->validated();

        // Tạo bình luận mới
        $comment = $product->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content'] ?? null,
            'rating'  => $data['rating'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // $path = $file->store('comment_images');
                $comment->commentImages()->create([
                    'image' => $file->store('comment_images'),
                ]);
            }
        }

        $comment->load([
            'user:id,name,email,avatar', 
            'commentImages:id,comment_id,image',
        ]);

        return $this->created('Đánh giá sản phẩm thành công', $comment);
    }

    public function update(CommentUpdateRequest $request, string $slug, string $id)
    {
        $data = $request->validated();

        $comment = Comment::find($id);

        $comment->update($data);

        if ($request->hasFile('images')) {

            if ($comment->commentImages()->exists()) $this->deleteCommentImages($comment);

            foreach ($request->file('images') as $file) {
                $comment->commentImages()->create([
                    'image' => $file->store('comment_images'),
                ]);
            }
        }

        $comment->load(['user', 'commentImages']);

        return $this->ok('Sửa bình luận thành công', $comment);
    }

    public function destroy(string $id)
    {
        
        $comment = Comment::find($id);
        
        if (!$comment) return $this->not_found('Bình luận không tồn tại');
        
        if (auth('sanctum')->id() != $comment->user_id) return $this->forbidden('Bạn không có quyền xóa bình luận này');    

        $this->deleteCommentImages($comment);

        $comment->delete();

        return $this->ok('Xóa bình luận thành công');
    }

    private function deleteCommentImages(Comment $comment)
    {
        $comment->load('commentImages:image');

        foreach ($comment->commentImages as $commentImage) {
            $this->delete_storage_file($commentImage, 'image');
        }

        $comment->unsetRelation('commentImages');
    }

}
