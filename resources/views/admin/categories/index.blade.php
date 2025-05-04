@extends('admin.layouts.master')
@section('title', 'Quản lý danh mục')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý danh mục</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li> --}}
                        <li class="breadcrumb-item active">Quản lý danh mục</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <div class="search-box me-2 mb-2 d-inline-block">
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="searchTableList" placeholder="Tìm kiếm...">
                                    <i class="bx bx-search-alt search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <a href="{{ route('admin.categories.create') }}"
                                    class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                    <i class="mdi mdi-plus me-1"></i>
                                    Thêm Danh Mục
                                </a>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Trạng thái</th>
                                    <th>Số lượng sản phẩm</th>
                                    <th>Thời gian thêm</th>
                                    <th>Thời gian cập nhật gần nhất</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="dtr-control sorting_1" tabindex="0">
                                            <div class="d-none">{{ $category->id }}</div>
                                            <div class="form-check font-size-16"> <input class="form-check-input"
                                                    type="checkbox" id="customerlistcheck-{{ $category->id }}"> <label
                                                    class="form-check-label"
                                                    for="customerlistcheck-{{ $category->id }}"></label> </div>
                                        </td>

                                        <td>
                                            {{ $category->name }}
                                        </td>

                                        <td>
                                            <span class="badge bg-success font-size-12 p-2">
                                                {{ $category->is_active ? 'Is Active' : 'No Active' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $category->products_count }}
                                        </td>

                                        <td>
                                            {{ $category->created_at }}
                                        </td>

                                        <td>
                                            {{ $category->updated_at }}
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle card-drop"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end" style="">
                                                    <li>
                                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                                            class="dropdown-item edit-list">
                                                            <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                            Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.categories.show', $category) }}"
                                                            class="dropdown-item edit-list">
                                                            <i class="fa-regular fa-eye font-size-16 text-warning me-1"
                                                                style="color: #FFD43B;"></i>
                                                            Chi Tiết
                                                        </a>
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
                        {{ $categories->links('admin.layouts.components.pagination') }}
                    </div>
                    <!-- end table responsive -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/admin/categories/index.js') }}"></script>
@endsection