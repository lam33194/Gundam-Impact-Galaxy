@extends('admin.layouts.master')
@section('title')
Đơn {{ $order->order_sku }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Đơn hàng {{ "#$order->id $order->order_sku" }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.orders.index') }}">Danh sách</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $order->order_sku }}</li>
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

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="row mb-4">
                            <div class="col-sm-3">
                                <div class="text-sm-end">
                                    <select name="status_order"
                                        class="form-select w-100 waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                        @foreach ($orderStatus as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ $order->status_order === $key ? 'selected' : '' }}
                                                {{ !in_array($key, $allowedStatuses) ? 'disabled' : '' }}>
                                                {{ $status['title'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="search-box me-2 mb-2 d-inline-block">
                                    <div class="text-sm-end">
                                        <button class="btn btn-primary">Lưu</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="d-flex justify-content-end align-items-center">
                                    <div class="me-4">
                                        <h5 class="mb-0">Tổng tiền: <span class="text-primary">{{ number_format($order->total_price, 0, ',', '.') }} đ</span></h5>
                                    </div>
                                    <div>
                                        <span class="badge {{ $order->isPaid() ? 'bg-success' : 'bg-danger' }} font-size-12 p-2">
                                            {{ $order->isPaid() ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div>
                        <ul class="verti-timeline list-unstyled">
                            @foreach ($orderStatus as $key => $status)
                                <li class="event-list pb-3 {{ $key === $order->status_order ? 'active' : '' }}">
                                    <div class="event-timeline-dot">
                                        <i class="bx bx-right-arrow-circle {{ $key === $order->status_order ? 'bx-fade-right' : '' }}"></i>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="bx {{ $status['icon'] }} h2 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>
                                                <h5>{{ $status['title'] }}</h5>
                                                <p class="text-muted">{{ $status['description'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- end card body -->
            </div>

            <div class="card">
                <h4 class="mb-sm-0 font-size-18 card-header">Thông tin người đặt</h4>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Avatar</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    @if($order->same_as_buyer)
                                    <th>Địa chỉ</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        @if($order->user->avatar && Storage::exists($order->user->avatar))
                                        <img src="{{ Storage::url($order->user->avatar) }}" width="70" alt="user_avatar">        
                                        @else
                                        <span>defaul user avatar</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->user_name }}</td>
                                    <td>{{ $order->user_email }}</td>
                                    <td>{{ $order->user_phone }}</td>
                                    @if($order->same_as_buyer)
                                    <td>{{ $order->user_address }}</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if(!$order->same_as_buyer)
            <div class="card">
                <h4 class="mb-sm-0 font-size-18 card-header">Thông tin người nhận</h4>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>{{ $order->ship_user_name }}</td>
                                    <td>{{ $order->ship_user_email }}</td>
                                    <td>{{ $order->ship_user_phone }}</td>
                                    <td>{{ $order->ship_user_address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="card">
                <h4 class="mb-sm-0 font-size-18 card-header">Chi tiết đơn hàng</h4>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Ảnh</th>
                                    <th>Sản phẩm gốc</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Màu</th>
                                    <th>Kích thước</th>
                                    <th>Tổng</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        <td>
                                            {{ $orderItem->id }}
                                        </td>

                                        <td>
                                            @if($orderItem->variant->image ?? $orderItem->product_img_thumbnail && Storage::exists($orderItem->variant->image ?? $orderItem->product_img_thumbnail))
                                            <img src="{{ Storage::url($orderItem->variant->image ?? $orderItem->product_img_thumbnail) }}" width="70" alt="variant_image">        
                                            @else
                                            <span>Defaul variant image</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.products.show', $orderItem->variant->product->id) }}">
                                                {{ limitTextLeng($orderItem->product_name, 45) }}
                                            </a>
                                        </td>

                                        <td>
                                            {{ formatPrice($orderItem->product_price_sale ?: $orderItem->product_price_regular) }}đ
                                        </td>

                                        <td>
                                            {{ $orderItem->quantity }}
                                        </td>

                                        <td>
                                            {{ $orderItem->variant_color_name }}
                                        </td>

                                        <td>
                                            {{ $orderItem->variant_size_name }}
                                        </td>

                                        <td>
                                            {{ calSubTotal($orderItem->quantity, $orderItem->product_price_sale ?: $orderItem->product_price_regular) }}đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection