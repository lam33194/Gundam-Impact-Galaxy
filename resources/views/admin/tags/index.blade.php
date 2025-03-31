@extends('admin.layouts.master')
@section('title', 'Tags')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Quản lý thẻ</h4>
        </div>
    </div>
</div>


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
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-success waves-effect waves-light mb-2 me-2 addCustomers-modal">
                                <i class="mdi mdi-plus me-1"></i>
                                Thêm
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>


                <div class="table-responsive min-vh-100">
                    <table class="table align-middle table-nowrap dt-responsive text-center nowrap w-100">
                        <thead class="">
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Thời gian tạo</th>
                                <th>Thời gian cập nhật</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($tags as $tag)
                            <tr>
                                <td>
                                    {{$loop->iteration}}
                                </td>

                                <td>
                                    {{ $tag->name }}
                                </td>

                                <td>
                                    {{ $tag->created_at->format('d/m/Y h:i:s') }}
                                </td>

                                <td>
                                    {{ $tag->updated_at->format('d/m/Y h:i:s') }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$tags->links()}}
                </div>

               

            </div>
        </div>
    </div>
</div>
@endsection
