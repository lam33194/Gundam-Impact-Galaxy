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
                            <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                <i class="mdi mdi-plus me-1"></i>
                                New Product
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>

                @if ($products->isNotEmpty())
                <div class="min-vh-100">
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

                            @foreach ($products as $product)
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">{{ $product->id }}</div>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox">
                                        <label class="form-check-label"></label>
                                    </div>
                                </td>

                                <td>
                                    @if ($product->thumb_image && Storage::exists($product->thumb_image))
                                    <img src="{{ Storage::url($product->thumb_image) }}" alt="{{ $product->name }}" style="height: 40px; width: 40px">
                                    @else
                                    <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" style="height: 40px; width: 40px">
                                    @endif
                                </td>

                                <td>
                                    {{ Str::length($product->name) > 10
                                            ? Str::limit($product->name, 10, '...')
                                            : $product->name }}
                                </td>

                                <td>
                                    {{ $product->sku }}
                                </td>

                                <td>
                                    {{ $product->category->name }}
                                </td>

                                <td>
                                    {{ number_format($product->price_regular) }}đ
                                </td>

                                <td>
                                    @if(!empty($product->price_sale))
                                    {{ number_format($product->price_sale) }}đ
                                    @else
                                    <span class="badge bg-danger font-size-12 p-2">
                                        No Sale
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info font-size-12 p-2">
                                        {{ $product->views }}
                                    </span>
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                            <li>
                                                <a href="{{ route('admin.products.edit', $product) }}" class="dropdown-item edit-list">
                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                    </i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.products.show', $product) }}" class="dropdown-item edit-list">
                                                    <i class="bx bx-show font-size-16 text-warning me-1"></i>
                                                    Show
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
                    {{ $products->links('admin.layouts.components.pagination') }}
                </div>

                @else
                <div class="min-vh-100">
                    <h1 class="text-danger">No Data</h1>
                </div>
                @endif

            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
@endsection
