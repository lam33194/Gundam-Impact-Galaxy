@extends('admin.layouts.master')
@section('title', 'Quản lý màu sắc')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Quản lý màu sắc</h4>
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
                            <a href="{{ route('admin.product-colors.create') }}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 addCustomers-modal">
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
                                <th>Tên</th>
                                <th>Mã màu</th>
                                <th>Thời gian tạo</th>
                                <th>Thời gian sửa</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($productColors as $color)
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">{{ $color->id }}</div>
                                    <div class="form-check font-size-16"> <input class="form-check-input" type="checkbox" id="customerlistcheck-{{ $color->id }}"> <label class="form-check-label" for="customerlistcheck-{{ $color->id }}"></label> </div>
                                </td>

                                <td>
                                    {{ $color->name }}
                                </td>

                                <td>
                                    {{ $color->code }}
                                </td>

                                <td>
                                    {{ $color->created_at }}
                                </td>

                                <td>
                                    {{ $color->updated_at }}
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="{{ route('admin.product-colors.edit', $color) }}" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1"></i>
                                                    Edit
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
                    {{ $productColors->links('admin.layouts.components.pagination') }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
