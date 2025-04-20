@extends('admin.layouts.master')
@section('title')
    Sửa bài viết {{ $post->title }}
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sửa bài viết: {{ $post->title }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.posts.index') }}">Danh sách</a>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}</li>
                </ol>
            </div>
        </div>

        <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input name="title" type="text" class="form-control" placeholder="Nhập tiêu đề bài viết..." value="{{ old('title', $post->title) }}" required>
                            @error('title')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea name="content" class="form-control" rows="10" placeholder="Nhập nội dung bài viết...">{{ old('content', $post->content) }}</textarea>
                            @error('content')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh hiện tại</label>
                            @if($post->thumbnail)
                                <div>
                                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail" style="max-width: 200px; margin-top: 10px;">
                                </div>
                            @else
                                <p>Chưa có hình ảnh</p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thay đổi hình ảnh (Thumbnail)</label>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            @error('thumbnail')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Cập nhật</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection