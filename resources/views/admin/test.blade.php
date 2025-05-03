@extends('admin.layouts.master')

@section('title', 'Test')

@section('content')
{{-- @foreach ($user->orders as $order)
    @foreach ($order->orderItems as $item)
        <h1>{{ $order->order_sku }}</h1>
        <h2>{{ $item->product_name }}</h2>
        <h3>{{ $item->variant_size_name }}</h3>
        <h3>{{ $item->variant_color_name }}</h3>
        <hr>
    @endforeach
@endforeach --}}

<!-- <button
    className="btn btn-link text-primary p-1"
    disabled={address.is_primary === 1}
    title="Đặt làm địa chỉ mặc định"
    onClick={() => handleSetDefaultAddress(address.id)}
>
    {address.is_primary === 1 ? (
        <i className="fas fa-star"></i>
    ) : (
        <i className="fa-regular fa-star"></i>
    )}
</button> -->

{{ $user }}
@endsection