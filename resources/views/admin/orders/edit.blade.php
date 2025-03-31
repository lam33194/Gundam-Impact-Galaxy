@php
use App\Models\Order;
@endphp
@extends('admin.layouts.master')
@section('title')
{{ $order->order_sku }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Đơn hàng: {{ $order->order_sku }}</h4>

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

                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <div class="search-box me-2 mb-2 d-inline-block">
                                <div class="text-sm-end">
                                    <button class="btn btn-primary">Lưu</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="text-sm-end">

                                @php
                                $orderStatus = Order::STATUS_ORDER;
                                $currentStatusIndex = array_search($order->status_order, array_keys($orderStatus));
                                @endphp

                                <select name="status_order" class="form-select w-25 waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                    @foreach ($orderStatus as $key => $status)
                                    @php
                                    $statusIndex = array_search($key, array_keys($orderStatus));
                                    $isDisabled = $statusIndex < $currentStatusIndex; @endphp <option value="{{ $key }}" {{ $order->status_order === $key ? 'selected' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                                        {{ $status }}
                                        </option>
                                        @endforeach
                                </select>



                            </div>

                        </div>
                    </div>
                </form>

                <div class="">

                    @php
                    $orderStatus = [
                    'pending' => [
                    'title' => 'Chờ xác nhận',
                    'description' => 'New common language will be more simple and regular than the existing.',
                    'icon' => 'bx-copy-alt'
                    ],
                    'confirmed' => [
                    'title' => 'Đã xác nhận',
                    'description' => 'To achieve this, it would be necessary to have uniform grammar.',
                    'icon' => 'bx-badge-check'
                    ],
                    'preparing_goods' => [
                    'title' => 'Đang chuẩn bị hàng',
                    'description' => 'To an English person, it will seem like simplified English.',
                    'icon' => 'bx-package'
                    ],
                    'shipping' => [
                    'title' => 'Đang vận chuyển',
                    'description' => 'It will be as simple as Occidental in fact, it will be Occidental.',
                    'icon' => 'bx-car'
                    ],
                    'delivered' => [
                    'title' => 'Đã giao hàng',
                    'description' => 'To an English person, it will seem like simplified English.',
                    'icon' => 'bx-badge-check'
                    ],
                    ];

                    $currentStatus = $order->status_order;
                    @endphp
                    <ul class="verti-timeline list-unstyled">
                        @foreach ($orderStatus as $key => $status)
                        <li class="event-list {{ $key === $currentStatus ? 'active' : '' }}">
                            <div class="event-timeline-dot">
                                <i class="bx bx-right-arrow-circle {{ $key === $currentStatus ? 'bx-fade-right' : '' }}"></i>
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
            <h4 class="mb-sm-0 font-size-18 card-header">Thông tin người dùng</h4>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                {{-- <th>Ảnh</th> --}}
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                {{-- <td>{{ $order->user_name }}</td> --}}
                                <td>{{ $order->user_name }}</td>
                                <td>{{ $order->user_email }}</td>
                                <td>{{ $order->user_phone }}</td>
                                <td>{{ $order->user_address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- <h5>Tên: {{ $order->user_name }}</h5>
                <h5>Email: {{ $order->user_email }}</h5>
                <h5>Số điện thoại: {{ $order->user_phone }}</h5>
                <h5>Địa chỉ: {{ $order->user_address }}</h5> --}}
            </div>
        </div>

        <div class="card">
            <h4 class="mb-sm-0 font-size-18 card-header">Chi tiết đơn hàng</h4>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Màu</th>
                                <th>Kích thước</th>
                                <th>Tổng</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($order->orderItems as $orderItem)
                            <tr>
                                <td>
                                    @if(Storage::exists($orderItem->product_img_thumbnail))
                                    <img src="{{ Storage::url($orderItem->product_img_thumbnail) }}" alt="" style="height: 40px; width: 40px">
                                    @endif
                                </td>

                                <td>
                                    {{ limitTextLeng($orderItem->product_name, 10) }}
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
