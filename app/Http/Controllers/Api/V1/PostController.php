<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;


class PostController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $posts = Post::with('user')->latest()->get();

        return $this->ok('Lấy danh sách bài viết thành công', $posts);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->first();

        if (!$post) return $this->not_found('Bài đăng không tồn tại');

        return $this->ok('Lấy chi tiết bài viết thành công', $post->load('user'));
    }
}