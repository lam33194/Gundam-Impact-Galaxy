@extends('admin.layouts.master')
@section('title', 'Products')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý sản phẩm</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Danh sách</li>
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
                        <div class="col-12">
                            <form action="{{ route('admin.products.index') }}" method="GET" id="filterForm">
                                <div class="card">
                                    <div class="card-body pb-0 pt-0">
                                        <!-- <h5 class="card-title mb-3">Bộ lọc sản phẩm</h5> -->

                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-3">
                                                <div class="search-box d-inline-block w-100">
                                                    <div class="position-relative">
                                                        <input id="searchTableList" type="text" class="form-control" name="search"
                                                            value="{{ request('search') }}"
                                                            placeholder="Tìm theo tên, SKU, ID...">
                                                        <i class="bx bx-search-alt search-icon"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-auto">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            id="is_active" value="1" {{ request('is_active') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_active">Đang hoạt động</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_hot_deal"
                                                            id="is_hot_deal" value="1" {{ request('is_hot_deal') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_hot_deal">Hot Deal</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_good_deal"
                                                            id="is_good_deal" value="1" {{ request('is_good_deal') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_good_deal">Good Deal</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_new"
                                                            id="is_new" value="1" {{ request('is_new') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_new">Sản phẩm mới</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_show_home"
                                                            id="is_show_home" value="1" {{ request('is_show_home') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_show_home">Hiển thị trang chủ</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_sale"
                                                            id="is_sale" value="1" {{ request('is_sale') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_sale">Đang giảm giá</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-auto ms-auto">
                                                <div class="text-sm-end">
                                                    <a href="{{ route('admin.products.create') }}"
                                                        class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                                        <i class="mdi mdi-plus me-1"></i>
                                                        Thêm sản phẩm
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary me-2">
                                                    <i class="bx bx-filter-alt me-1"></i> Lọc
                                                </button>
                                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                                    <i class="bx bx-reset me-1"></i> Đặt lại
                                                </a>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($products->isNotEmpty())
                            <div>
                                <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                                    <thead class="table-light">
                                        <tr>
                                            <!-- <th></th> -->
                                            <th>ID</th>
                                            <th>Ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Sku</th>
                                            <th>Danh mục</th>
                                            <th>Giá gốc
                                                <a href={{ route('admin.products.index', array_merge(request()->query(), [
                                                    'sort_by' => 'price_regular',
                                                    'sort_direction' => ($sortBy == 'price_regular' && $sortDirection == 'asc') ? 'desc' : 'asc'
                                                ])) }}>
                                                    {{ $sortBy == 'price_regular' ? ($sortDirection == 'asc' ? '↑' : '↓') : '⇵' }}
                                                </a>
                                            </th>
                                            <th>Giá sale
                                                <a href={{ route('admin.products.index', array_merge(request()->query(), [
                                                    'sort_by' => 'price_sale',
                                                    'sort_direction' => ($sortBy == 'price_sale' && $sortDirection == 'asc') ? 'desc' : 'asc'
                                                ])) }}>
                                                    {{ $sortBy == 'price_sale' ? ($sortDirection == 'asc' ? '↑' : '↓') : '⇵' }}
                                                </a>
                                            </th>
                                            {{-- <th>Lượt xem</th> --}}
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                {{-- <td class="dtr-control sorting_1" tabindex="0">
                                                    <div class="d-none">{{ $product->id }}</div>
                                                    <div class="form-check font-size-16">
                                                        <input class="form-check-input" type="checkbox">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td> --}}
                                                <td>{{$product->id}}</td>
                                                <td>
                                                    <a href="{{ route('admin.products.show', $product->id) }}">
                                                    @if ($product->thumb_image && Storage::exists($product->thumb_image))
                                                        <img src="{{ Storage::url($product->thumb_image) }}" alt="{{ $product->name }}" width="50" height="auto">
                                                    @else
                                                        <img src="https://laravel.com/img/logomark.min.svg" alt="avatar default" width="50" height="auto">
                                                    @endif
                                                    </a>
                                                </td>
                                                <td>{{ Str::length($product->name) > 20 ? Str::limit($product->name, 20, '...') : $product->name }}
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
                                                {{-- <td>
                                                    <span class="badge bg-info font-size-12 p-2">
                                                        {{ $product->views }}
                                                    </span>
                                                </td> --}}
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                                    class="dropdown-item edit-list">
                                                                    <i class="mdi mdi-pencil font-size-16 text-success me-1">
                                                                    </i>
                                                                    Sửa
                                                                </a>
                                                            <li>
                                                                <form method="POST"
                                                                    action="{{ route('admin.products.destroy', $product->id) }}"
                                                                    class="d-inline-block">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item edit-list"
                                                                        onclick="return confirm('Bạn có muốn xóa không')">
                                                                        <i class="fas fa-trash-alt text-danger font-size-16 me-2"></i>
                                                                        Xóa
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

@section('script')
    <script>
        document.addEventListener('keyup', function (event) {
            if (event.key === '/' && !event.altKey && !event.ctrlKey && !event.metaKey) {
                // Kiểm tra nếu không phải đang nhập trong input hoặc textarea
                const activeElement = document.activeElement;
                if (activeElement.tagName !== 'INPUT' && activeElement.tagName !== 'TEXTAREA') {
                    event.preventDefault();
                    const searchInput = document.getElementById('searchTableList');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }
            }
        });
    </script>
@endsection