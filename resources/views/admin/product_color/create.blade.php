@extends('admin.layouts.master')
@section('title', 'Add New Color')
@section('style')
<link href="{{ asset('assets/theme/admin/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Thêm mới màu</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.product-colors.index') }}">Danh sách</a>
                    </li>
                    <li class="breadcrumb-item active">Thêm mới màu</li>
                </ol>
            </div>
        </div>


        <form action="{{ route('admin.product-colors.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tên</label>
                            <input name="name" type="text" class="form-control" placeholder="Nhập tên màu..." value="{{ old('name') }}" required>
                            @error('name')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Màu sắc</label>
                            <input type="text" class="form-control" id="colorpicker-default" name="code" value="#cccc" />
                            @error('code')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/theme/admin/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
<script src="{{ asset('assets/theme/admin/js/pages/form-advanced.init.js') }}"></script>
@endsection
