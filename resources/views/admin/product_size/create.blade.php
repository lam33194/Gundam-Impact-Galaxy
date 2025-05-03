@extends('admin.layouts.master')
@section('title', 'Add New Size')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Thêm Size</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.product-sizes.index') }}">Quản lý Size</a>
                    </li>
                    <li class="breadcrumb-item active">Thêm Size</li>
                </ol>
            </div>
        </div>


        <form action="{{ route('admin.product-sizes.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tên</label>
                            <input name="name" type="text" class="form-control" placeholder="Enter color name..." value="{{ old('name') }}" required>
                            @error('name')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
