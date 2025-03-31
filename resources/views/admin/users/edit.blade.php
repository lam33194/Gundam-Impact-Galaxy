@php
use App\Models\User;
use Illuminate\Support\Facades\Storage;
@endphp

@extends('admin.layouts.master')
@section('title')
Edit User: {{ $user->name }}
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit User: {{ $user->name }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    <li class="breadcrumb-item active">Edit User</li>
                </ol>
            </div>
        </div>


        <form id="form-user-create" action="{{ route('admin.users.update', $user) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Image</label>

                                <div class="text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="position-absolute bottom-0 end-0">
                                            <label for="project-image-input" class="mb-0" data-bs-toggle="tooltip"
                                                data-bs-placement="right" title="Select Image">
                                                <div class="avatar-xs">
                                                    <div
                                                        class="avatar-title bg-light border rounded-circle text-muted cursor-pointer shadow font-size-16">
                                                        <i class='bx bxs-image-alt'></i>
                                                    </div>
                                                </div>
                                            </label>
                                            <input class="form-control d-none" value="" id="project-image-input"
                                                type="file" accept="image/png, image/gif, image/jpeg"
                                                name="user[avatar]" onchange="previewImage(event)">
                                        </div>
                                        <div class="avatar-lg">
                                            <div class="avatar-title bg-light rounded-circle">
                                                @if($user->avatar && Storage::exists($user->avatar))
                                                <a class="image-popup-no-margins"
                                                    href="{{ Storage::url($user->avatar) }}">
                                                    <img src="{{ Storage::url($user->avatar) }}" id="projectlogo-img"
                                                        class="rounded-circle avatar-lg" />
                                                </a>
                                                @else
                                                <img src id="projectlogo-img" class="avatar-lg rounded-circle h-auto" />
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input name="user[name]" type="text"
                                            class="form-control @error('user.name') is-invalid @enderror"
                                            placeholder="Enter user name..." value="{{ $user->name }}">
                                        @error('user.name')
                                        <div class="text-danger fst-italic">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input name="user[email]" type="email"
                                            class="form-control @error('user.email') is-invalid @enderror"
                                            placeholder="Enter user email..." value="{{ $user->email }}">
                                        @error('user.email')
                                        <div class="text-danger fst-italic">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input name="user[password]" type="password"
                                            class="form-control @error('user.password') is-invalid @enderror"
                                            placeholder="Enter user password..." value="{{ old('user.password') }}">
                                        @error('user.password')
                                        <div class="text-danger fst-italic">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input name="user[phone]" type="text"
                                            class="form-control @error('user.phone') is-invalid @enderror"
                                            placeholder="Enter user phone..." value="{{ $user->phone }}">
                                        @error('user.phone')
                                        <div class="text-danger fst-italic">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Publish</h5>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="user[role]">
                                    <option value="member" {{ $user->role === User::ROLE_MEMBER ? 'selected' : '' }}>
                                        Member
                                    </option>
                                    <option value="admin" {{ $user->role === User::ROLE_ADMIN ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                </select>
                            </div>

                            <div>
                                <div class="form-check form-switch mb-3">
                                    <label class="form-check-label">is_active</label>
                                    <input class="form-check-input" type="checkbox" name="user[is_active]" {{
                                        $user->is_active ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>

                <!-- data old -->
                <input type="hidden" name="passwordOld" value="{{ $user->password }}">
                <input type="hidden" name="imageOld" value="{{ $user->avatar }}">
                <!-- end col -->

                <div class="col-lg-8">
                    <div class="text-end mb-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/theme/admin/libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('assets/js/admin/users/edit.js') }}"></script>
@endsection
