@extends('admin.layouts.master')
@section('title', 'Categories')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Quản lý danh mục</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li> --}}
                    <li class="breadcrumb-item active">Danh mục</li>
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
                                <input type="text" class="form-control" id="searchTableList" placeholder="Search...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-success waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                <i class="mdi mdi-plus me-1"></i>
                                Thêm
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive min-vh-100">
                    <table class="table align-middle text-center table-nowrap dt-responsive nowrap w-100">
                        <thead class="">
                            <tr>
                                <th>STT</th>
                                <th>Tên</th>
                                <th>Hoạt động</th>
                                <th>Thời gian tạo</th>
                                <th>Thời gian cập nhật</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($categories as $category)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $category->name }}
                                </td>

                                <td>
                                    <span class="badge bg-success font-size-12 p-2">
                                        {{ $category->is_active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $category->created_at->format('d/m/Y h:i:s') }}
                                </td>

                                <td>
                                    {{ $category->updated_at->format('d/m/Y h:i:s') }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $categories->links() }}
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
