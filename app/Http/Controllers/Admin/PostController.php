<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    private const VIEW_PATH = 'admin.posts.';

    public function index()
    {
        $posts = Post::query()->latest('id')->paginate(20);

        return view(self::VIEW_PATH . __FUNCTION__, compact('posts'));
    }

}