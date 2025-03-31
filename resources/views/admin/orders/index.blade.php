@php
use App\Models\Order;
@endphp
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
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <div class="search-box me-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="searchTableList" placeholder="Search...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive min-vh-100">
                    <table class="table align-middle table-nowrap dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order Code</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Status Order</th>
                                <th>Status Payment</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td class="dtr-control sorting_1" tabindex="0">
                                    <div class="d-none">{{ $order->id }}</div>
                                    <div class="form-check font-size-16"> <input class="form-check-input" type="checkbox" id="customerlistcheck-11"> <label class="form-check-label" for="customerlistcheck-11"></label> </div>
                                </td>

                                <td>
                                    {{ $order->order_sku }}
                                </td>

                                <td>
                                    {{ limitTextLeng($order->user_name, 10) }}
                                </td>

                                <td>
                                    {{ $order->orderItems->count() }}
                                </td>

                                <td>
                                    <span class="badge {{ statusOrderClass($order->status_order) }} font-size-12 p-2">
                                        {{ matchStatusOrder($order->status_order) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge {{ statusPaymentClass($order->status_payment) }} font-size-12 p-2">
                                        {{ matchStatusPayMent($order->status_payment) }}
                                    </span>
                                </td>

                                <td>
                                    {{ formatPrice($order->total_price) }}đ
                                </td>

                                <td>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning btn-sm">
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
@endsection
