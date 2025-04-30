@extends('admin.layouts.master')
@section('title', 'Show product')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Sản phẩm {{ $product->name }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.products.index') }}">Chi tiết sản phẩm</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $product->sku }}</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">

            <!-- product Information -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 font-size-18">Thông tin sản phẩm</h4>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit"></i> Chỉnh sửa
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="font-size-15">Thông tin cơ bản</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="35%">Tên sản phẩm</th>
                                                <td>{{ $product->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>SKU</th>
                                                <td>{{ $product->sku }}</td>
                                            </tr>
                                            <tr>
                                                <th>Slug</th>
                                                <td>{{ $product->slug }}</td>
                                            </tr>
                                            <tr>
                                                <th>Danh mục</th>
                                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tags</th>
                                                <td>
                                                    @if($product->tags->count() > 0)
                                                        @foreach($product->tags as $tag)
                                                            <span class="badge bg-info p-1 fs-6 me-1 mb-1">{{ $tag->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <div class="alert alert-info mb-0">Chưa có tags</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Giá gốc</th>
                                                <td>{{ number_format($product->price_regular) }} đ</td>
                                            </tr>
                                            <tr>
                                                <th>Giá khuyến mãi</th>
                                                <td>{{ number_format($product->price_sale) }} đ</td>
                                            </tr>
                                            <tr>
                                                <th>Lượt xem</th>
                                                <td>{{ number_format($product->views) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="font-size-15">Trạng thái</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="35%">Trạng thái</th>
                                                <td>
                                                    @if($product->is_active)
                                                        <span class="badge fs-6 p-1 bg-success">Đang hoạt động</span>
                                                    @else
                                                        <span class="badge fs-6 p-1 bg-danger">Không hoạt động</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Hot Deal</th>
                                                <td>
                                                    @if($product->is_hot_deal)
                                                        <span class="badge fs-6 p-1 bg-success">Có</span>
                                                    @else
                                                        <span class="badge fs-6 p-1 bg-secondary">Không</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Good Deal</th>
                                                <td>
                                                    @if($product->is_good_deal)
                                                        <span class="badge fs-6 p-1 bg-success">Có</span>
                                                    @else
                                                        <span class="badge fs-6 p-1 bg-secondary">Không</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sản phẩm mới</th>
                                                <td>
                                                    @if($product->is_new)
                                                        <span class="badge fs-6 p-1 bg-success">Có</span>
                                                    @else
                                                        <span class="badge fs-6 p-1 bg-secondary">Không</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Hiển thị trang chủ</th>
                                                <td>
                                                    @if($product->is_show_home)
                                                        <span class="badge fs-6 p-1 bg-success">Có</span>
                                                    @else
                                                        <span class="badge fs-6 p-1 bg-secondary">Không</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Ngày tạo</th>
                                                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Cập nhật lần cuối</th>
                                                <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- galleries -->
            <div class="card">
                <h4 class="mb-sm-0 font-size-18 card-header">Ảnh sản phẩm</h4>
                <div class="card-body">
                    <!-- Product Image -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0 font-size-14">Ảnh thumbnail</h6>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($product->thumb_image)
                                                <img src="{{ Storage::url($product->thumb_image) }}" class="img-fluid"
                                                    style="max-height: 180px;" alt="Thumbnail">
                                            @else
                                                <div class="alert alert-info mb-0">Chưa có ảnh</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0 font-size-14">Thư viện ảnh</h6>
                                        </div>
                                        <div class="card-body">
                                            @if($product->galleries->count() > 0)
                                                <div class="row">
                                                    @foreach($product->galleries as $gallery)
                                                        <div class="col-md-3 mb-3">
                                                            <img src="{{ Storage::url($gallery->image) }}" class="img-fluid rounded"
                                                                style="max-height: 150px;" alt="Gallery image">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-info mb-0">Chưa có ảnh trong thư viện</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- variants -->
            <div class="card">
                <h4 class="mb-sm-0 font-size-18 card-header">Biến thể</h4>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Kích thước</th>
                                    <th>Màu</th>
                                    <th>Số lượng</th>
                                    <th>Ảnh</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($product->variants as $variant)
                                    <tr>
                                        <td>
                                            {{ $variant->id }}
                                        </td>

                                        <td>
                                            {{ $variant->size->name }}
                                        </td>

                                        <td>
                                            {{ $variant->color->name }}
                                        </td>

                                        <td>
                                            {{ $variant->quantity }}
                                        </td>

                                        <td>
                                            @if($variant->image && Storage::exists($variant->image))
                                            <img src="{{ Storage::url($variant->image) }}" width="70"
                                                alt="variant_image">
                                            @else
                                            <span>defaul image</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- description -->
            <div class="card">
                <h4 class="mb-sm-0 font-size-18 card-header">Mô tả</h4>
                <div class="card-body">
                    <!-- Product Description -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 font-size-15">Mô tả ngắn</h5>
                                </div>
                                <div class="card-body">
                                    @if($product->description)
                                        <div class="border p-3 rounded">
                                            {!! $product->description !!}
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">Chưa có mô tả</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Content -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 font-size-15">Nội dung chi tiết</h5>
                                </div>
                                <div class="card-body">
                                    @if($product->content)
                                        <div class="border p-3 rounded">
                                            <p>{!! $product->content !!}</p>
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">Chưa có nội dung chi tiết</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection