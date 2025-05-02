<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Traits\StorageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use StorageFile;
    private const VIEW_PATH = 'admin.posts.';

    public function index()
    {
        $posts = Post::query()->latest('id')->paginate(20);

        return view(self::VIEW_PATH . __FUNCTION__, compact('posts'));
    }

    public function create()
    {
        $admins = User::where('role', User::ROLE_ADMIN)->get();
        return view(self::VIEW_PATH . __FUNCTION__, compact('admins'));
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')->store('posts_thumb');
            }
            $data['slug'] = Str::slug($data['title']);
            Post::create($data);
            Toastr::success(null, 'Thêm bài viết thành công');
            return redirect()->route('admin.posts.index');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Toastr::error(null, $e->getMessage());
            return back();
        }
    }

    public function edit(Post $post)
    {
        return view(self::VIEW_PATH . __FUNCTION__, compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        try {
            if ($request->hasFile('thumbnail')) {
                $this->delete_storage_file($post, 'thumbnail');
    
                // upload file vào storage
                $data['thumbnail'] = $request->file('thumbnail')->store('posts_thumb');
            }
            $post->update($data);
            Toastr::success(message: null, title: 'Sửa bài viết thành công');
            return back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Toastr::error(null, 'Sửa bài viết không thành công');
            return back();
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::query()->findOrFail($id);
            $post->delete();
            return back()->with('success', 'Xóa bài viết thành công');
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết',
            ]);
        }
    }
}