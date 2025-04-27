@extends('admin.layouts.master')
@section('title', 'Quản lý bài viết')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Quản lý bài viết</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="searchTableList" placeholder="Search...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                <i class="mdi mdi-plus me-1"></i>
                                Thêm mới
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive min-vh-100">
                    <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>  </th>
                                <th>Ảnh</th>
                                <th>Thời gian tạo</th>
                                <th>Thời gian sửa</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">{{ $post->id }}</div>
                                    <div class="form-check font-size-16"> 
                                        <input class="form-check-input" type="checkbox" id="postlistcheck-{{ $post->id }}"> 
                                        <label class="form-check-label" for="postlistcheck-{{ $post->id }}"></label> 
                                    </div>
                                </td>

                                <td>
                                    {{ $post->title }}
                                </td>

                                <td>
                                    {{ Str::limit($post->content, 50) }}
                                </td>

                                <td>
                                    <td>
                                        @if ($post->thumbnail && Storage::exists($post->thumbnail))
                                            <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}" width="50" height="auto">
                                        @else
                                            <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" width="50" height="auto">
                                        @endif
                                    </td>
                                </td>

                                <td>
                                    {{ $post->created_at }}
                                </td>

                                <td>
                                    {{ $post->updated_at }}
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.posts.edit', $post) }}" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item remove-list" onclick="return confirm('Chắc chắn xóa')" type="submit" class="btn btn-danger">
                                                        <i class="mdi mdi-trash-can font-size-12 text-danger me-1"></i>Xóa
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    {{ $posts->links('admin.layouts.components.pagination') }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection