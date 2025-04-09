@extends('admin.layouts.master')
@section('title', 'Chi tiết Mã Giảm Giá')
@section('content')

<!-- Start Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-20 fw-bold">
                <i class="bx bx-receipt text-primary me-2"></i> Chi tiết mã giảm giá
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}" class="text-decoration-none text-muted">Mã giảm giá</a></li>
                    <li class="breadcrumb-item active fw-bold">{{ $voucher->code }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End Page Title -->

<div class="row">
    <div class="col-lg-9">
        <div class="card border-1 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    Mã giảm giá: <span class="text-warning">{{ $voucher->code }}</span>
                </h5>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light btn-sm">
                    <i class="bx bx-arrow-back"></i> Quay lại
                </a>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Cột 1 -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong><i class="bx bx-font"></i> Tiêu đề:</strong>
                            <p class="text-muted">{{ $voucher->title }}</p>
                        </div>
                        <div class="mb-3">
                            <strong><i class="bx bx-dollar-circle"></i> Giảm giá:</strong>
                            <p class="text-muted">{{ number_format($voucher->discount, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>

                    <!-- Cột 2 -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong><i class="bx bx-calendar-check"></i> Ngày bắt đầu:</strong>
                            <p class="text-muted">
                                {{ \Carbon\Carbon::parse($voucher->start_date_time)->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <strong><i class="bx bx-calendar-x"></i> Ngày kết thúc:</strong>
                            <p class="text-muted">
                                {{ \Carbon\Carbon::parse($voucher->end_date_time)->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="bx bx-info-circle"></i> Mô tả:</strong>
                            <p class="text-muted">{{ $voucher->description }}</p>
                        </div>
                        
                    </div>
                </div>

                
            </div>
        </div>
    </div>
