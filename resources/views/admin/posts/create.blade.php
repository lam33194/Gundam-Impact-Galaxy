@extends('admin.layouts.master')
@section('title', 'Thêm mới bài viết')
@section('style')
<!-- Không cần thêm CSS cho trình soạn thảo -->
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Thêm mới bài viết</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li

 class="breadcrumb-item">
                        <a href="{{ route('admin.posts.index') }}">Danh sách</a>
                    </li>
                    <li class="breadcrumb-item active">Thêm mới bài viết</li>
                </ol>
            </div>
        </div>

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input name="title" type="text" class="form-control" placeholder="Nhập tiêu đề bài viết..." value="{{ old('title') }}" required>
                            @error('title')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Người tạo</label>
                            <select name="user_id" class="form-control" required>
                                <option value="">Chọn người tạo</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ old('user_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger fst-italic mt-2">
                                    * {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <textarea name="content" class="form-control" rows="10" placeholder="Nhập nội dung bài viết...">{{ old('content') }}</textarea>
                            @error('content')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hình ảnh (Thumbnail)</label>
                            <input type="file" class="form-control" name="thumbnail" accept="image/*">
                            @error('thumbnail')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

