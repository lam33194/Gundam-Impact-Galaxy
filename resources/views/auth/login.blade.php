@extends('auth.layouts.master')
@section('title', 'Login')
@section('content')
<div class="card overflow-hidden">
    <div class="bg-primary-subtle">
        <div class="row">
            <div class="col-7">
                <div class="text-primary p-4">
                    <h5 class="text-primary">Chào mừng</h5>
                </div>
            </div>
            <div class="col-5 align-self-end">
                <img src="{{ asset('assets/theme/admin/images/profile-img.png') }}" alt="" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="auth-logo">
            <a href="index.html" class="auth-logo-light">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{ asset('assets/theme/admin/images/logo-light.svg') }}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>

            <a href="index.html" class="auth-logo-dark">
                <div class="avatar-md profile-user-wid mb-4">
                    <span class="avatar-title rounded-circle bg-light">
                        <img src="{{ asset('assets/theme/admin/images/logo.svg') }}" alt="" class="rounded-circle" height="34">
                    </span>
                </div>
            </a>
        </div>
        <div class="p-2">
            <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <span class="text-danger">*</span>
                        Email
                    </label>
                    <input type="email" name="email" class="form-control" id="Email" value="{{ old('email') }}" placeholder="Enter email">
                    @error('email')
                    <small class="text-danger fst-italic">
                        <span class="required">*</span>
                        {{ $message }}
                    </small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        <span class="text-danger">*</span>
                        Mật Khẩu
                    </label>
                    <div class="input-group auth-pass-inputgroup">
                        <input type="password" name="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                        <button class="btn btn-light " type="button" id="password-addon">
                            <i class="mdi mdi-eye-outline"></i>
                        </button>
                    </div>
                    @error('password')
                    <small class="text-danger fst-italic">
                        <span class="required">*</span>
                        {{ $message }}
                    </small>
                    @enderror
                </div>

                <div class="mt-3 d-grid">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Đăng Nhập
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
