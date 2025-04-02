@extends('admin.layouts.master')
@section('title', 'Danh sách mã giảm giá')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        #datatable_length select {
            width: 60px;
        }

        #datatable thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <h6 class="mb-sm-0 font-size-16">Danh sách mã giảm giá</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a href="#" class="btn btn-primary me-2">+ Thêm mới</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã giảm giá</th>
                                    <th>Giảm giá (VNĐ)</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>BIZZARE</td>
                                    <td>20,000 VNĐ</td>
                                    <td>13/03/2025 14:38:00</td>
                                    <td>13/03/2025 14:38:00</td>
                                    <td>?</td>

                                    <td class="text-center">
                                        <a href="#">
                                            <button title="Chi tiết" class="btn btn-success btn-sm" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </a>

                                        <a href="#">
                                            <button title="Sửa" class="btn btn-warning btn-sm" type="button">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td>1</td>
                                    <td>BIZZARE</td>
                                    <td>20,000 VNĐ</td>
                                    <td>13/03/2025 14:38:00</td>
                                    <td>13/03/2025 14:38:00</td>
                                    <td>?</td>

                                    <td class="text-center">
                                        <a href="#">
                                            <button title="Chi tiết" class="btn btn-success btn-sm" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </a>

                                        <a href="#">
                                            <button title="Sửa" class="btn btn-warning btn-sm" type="button">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection