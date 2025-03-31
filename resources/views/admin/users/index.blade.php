@php
    use App\Models\User;
@endphp

@extends('admin.layouts.master')
@section('title', 'Users')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Users</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Users</li>
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
                        <div class="col-sm-8">
                            <div class="text-sm-end">
                                <a href="{{ route('admin.users.create') }}"
                                    class="btn btn-success waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                    <i class="mdi mdi-plus me-1"></i>
                                    Thêm
                                </a>
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive min-vh-100">
                        @if ($users->isNotEmpty())
                            <div class="min-vh-100">
                                <table class="table align-middle table-nowrap text-center dt-responsive nowrap w-100">
                                    <thead class="">
                                        <tr>
                                            <th>STT</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Is_active</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td>
                                                    @if ($user->avatar && Storage::exists($user->avatar))
                                                        <img src="{{ Storage::url($user->avatar) }}"
                                                            alt="{{ $user->name }}" style="height: 40px; width: 40px">
                                                    @else
                                                        <img src="https://laravel.com/img/logomark.min.svg"
                                                            alt="avatar default" style="height: 30px; width: 30px">
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ $user->name }}
                                                </td>

                                                <td>
                                                    {{ $user->email }}
                                                </td>

                                                <td>
                                                    <span @class(['text-danger' => empty($user->phone)])>
                                                        {{ $user->phone ? Str::mask($user->phone, '*', 6) : 'No Data' }}
                                                    </span>
                                                </td>

                                                <td>
                                                    @if ($user->role === User::ROLE_ADMIN)
                                                        <span class="badge bg-success font-size-12 p-2">
                                                            {{ Str::title($user->role) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-info font-size-12 p-2">
                                                            {{ Str::title($user->role) }}
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <span
                                                        class="badge font-size-12 p-2 {{ $user->is_active ? 'bg-success' : ' bg-danger' }}">
                                                        {{ $user->is_active ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <a href="{{ route('admin.users.edit', $user) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit">
                                                        </i>

                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $users->links() }}
                            </div>
                        @else
                            <div class="min-vh-100 text-center">
                                <h1 class="text-danger">Không có người dùng nào !!!</h1>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
