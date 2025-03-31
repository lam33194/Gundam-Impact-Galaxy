@extends('admin.layouts.master')
@section('title')
Edit Color {{ $productColor->name }}
@endsection
@section('style')
<link href="{{ asset('assets/theme/admin/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Color: {{ $productColor->name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.product-colors.index') }}">List Color</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Color: {{ $productColor->name }}</li>
                </ol>
            </div>
        </div>


        <form action="{{ route('admin.product-colors.update', $productColor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" class="form-control" placeholder="Enter color name..." value="{{ $productColor->name }}" required>
                            @error('name')
                            <div class="text-danger fst-italic mt-2">
                                * {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Màu sắc</label>
                            <input type="text" class="form-control" id="colorpicker-default" name="code" value="{{ $productColor->code }}" />
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
