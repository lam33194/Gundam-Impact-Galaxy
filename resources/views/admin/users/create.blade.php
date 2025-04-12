@extends('admin.layouts.master')
@section('title', 'New User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Create User</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Users</a>
                        </li>
                        <li class="breadcrumb-item active">Create New</li>
                    </ol>
                </div>
            </div>


            <form id="form-user-create" action="{{ route('admin.users.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
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
                                                    <img src id="projectlogo-img" class="avatar-lg h-auto rounded-circle" />
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
                                                placeholder="Enter user name..." value="{{ old('user.name') }}">
                                            @error('user.name')
                                                <div class="text-danger fst-italic">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input name="user[email]" type="email"
                                                class="form-control @error('user.email') is-invalid @enderror"
                                                placeholder="Enter user email..." value="{{ old('user.email') }}">
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
                                                placeholder="Enter user phone..." value="{{ old('user.phone') }}">
                                            @error('user.phone')
                                                <div class="text-danger fst-italic">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input name="address[address]" type="text" class="form-control"
                                                placeholder="Enter user address..." value="{{ old('address.address') }}">
                                            @error('address.address')
                                                <div class="text-danger fst-italic">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">District</label>
                                            <select class="form-select" id="district" value="{{ old('district') }}">
                                                <option value="0" selected>Chọn Quận Huyện</option>
                                            </select>

                                            @error('address.district')
                                                <div class="text-danger fst-italic">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <select class="form-select" id="city" value="{{ old('address.city') }}">
                                                <option value="0" selected>Chọn Thành Phố</option>
                                            </select>

                                            @error('address.city')
                                                <div class="text-danger fst-italic">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ward</label>
                                            <select class="form-select" id="ward" value="{{ old('address.ward') }}">
                                                <option value="0" selected>Chọn Phường Xã</option>
                                            </select>

                                            @error('address.ward')
                                                <div class="text-danger fst-italic">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <input type="hidden" name="address[city]" id="city-hidden">
                                    <input type="hidden" name="address[district]" id="district-hidden">
                                    <input type="hidden" name="address[ward]" id="ward-hidden">
                                </div>
                            </div>
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
                                        <option value="member" selected>Member</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch mb-3">
                                        <label class="form-check-label">is_active</label>
                                        <input class="form-check-input" type="checkbox" checked="" name="user[is_active]">
                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-8">
                        <div class="text-end mb-4">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script')

    <script src="{{ asset('assets/theme/admin/libs/dropzone/dropzone-min.js') }}"></script>
    <script src="{{ asset('assets/js/admin/users/create.js') }}"></script>
    {{--
    <script src="{{ asset('assets/theme/admin/js/pages/form-file-upload.init.js') }}"></script> --}}
    {{--
    <script src="{{ asset('assets/theme/admin/js/pages/project-create.init.js') }}"></script> --}}
@endsection