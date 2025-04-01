@extends('admin.layouts.master')
@section('title', 'Products')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Products</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Products</li>
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
                            <a href="" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                <i class="mdi mdi-plus me-1"></i>
                                New Product
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>

                <div>
                    <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Sku</th>
                                <th>Category</th>
                                <th>Price_Regular</th>
                                <th>Price_Sale</th>
                                <th>View</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">1</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                </td>

                                <td>
                                    Mô hình gundam aowijgoigjoiajfoa
                                </td>

                                <td>
                                    DX-2104
                                </td>

                                <td>
                                    Accessory Model
                                </td>

                                <td>
                                    120.000đ
                                </td>

                                <td>
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        100
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    {{-- $products->links() --}}
                </div>

            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
@endsection
