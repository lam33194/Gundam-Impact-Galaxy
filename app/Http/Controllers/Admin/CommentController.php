<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private const PATH_VIEW = 'admin.comments.';

    public function index()
    {
        $comments = Comment::with(['user', 'product'])->latest('id')->paginate(10);

        return view(self::PATH_VIEW . __FUNCTION__, compact('comments'));
    }

    
}
