@extends('admin.layouts.master')
@section('title', 'Orders')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Orders</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Orders</li>
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
                    <form action="{{ route('admin.orders.index') }}" method="GET">
                        <div class="row mb-3">
                            <div class="col-md-auto mb-2">
                                <div class="search-box me-2 d-inline-block">
                                    <div class="position-relative">
                                        <input type="text" name="search" class="form-control" id="searchTableList"
                                            placeholder="Tìm kiếm" value="{{ request('search') }}">
                                        <i class="bx bx-search-alt search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bx bx-filter-alt me-1"></i> Lọc kết quả
                                </button>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-reset me-1"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Từ ngày</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Đến ngày</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Trạng thái đơn hàng</label>
                                <select name="status_order" class="form-select">
                                    <option value="">Tất cả trạng thái</option>

                                    @foreach (\App\Models\Order::STATUS_ORDER as $key => $value)
                                        <option value="{{$key}}" {{ request('status_order') == $key ? 'selected' : '' }}>
                                            {{$value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label">Trạng thái thanh toán</label>
                                <select name="status_payment" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    @foreach (\App\Models\Order::STATUS_PAYMENT as $key => $value)
                                        <option value="{{$key}}" {{ request('status_order') == $key ? 'selected' : '' }}>
                                            {{$value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive min-vh-100">
                        <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Mã đơn hàng</th>
                                    <th>Người đặt</th>
                                    <th>Phương thức thanh toán</th>
                                    <th>Trạng thái đơn hàng</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Tổng tiền</th>
                                    <th>Thời gian</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="dtr-control sorting_1" tabindex="0">
                                            <div class="d-none">{{ $order->id }}</div>
                                            <div class="form-check font-size-16"> <input class="form-check-input"
                                                    type="checkbox" id="customerlistcheck-11"> <label class="form-check-label"
                                                    for="customerlistcheck-11"></label> </div>
                                        </td>

                                        <td>{{ $order->id }}</td>

                                        <td>{{ $order->order_sku }}</td>

                                        <td>{{ limitTextLeng($order->user_name, 10) }}</td>

                                        <td>{{ $order->type_payment }}</td>

                                        <td>
                                            <span class="badge {{ statusOrderClass($order->status_order) }} font-size-12 p-2">
                                                {{ matchStatusOrder($order->status_order) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span
                                                class="badge {{ statusPaymentClass($order->status_payment) }} font-size-12 p-2">
                                                {{ matchStatusPayMent($order->status_payment) }}
                                            </span>
                                        </td>

                                        <td>{{ formatPrice($order->total_price) }}đ</td>

                                        <td>{{ $order->created_at->diffForHumans() }}</td>

                                        <td>
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- end table responsive -->

                    <div class="row">
                        {{ $orders->links('admin.layouts.components.pagination') }}
                    </div>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>

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