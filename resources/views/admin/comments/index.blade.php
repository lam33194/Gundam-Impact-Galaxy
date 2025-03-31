@extends('admin.layouts.master')
@section('title', 'Comemnts')
@section('style')
    <link href="{{ asset('assets/theme/admin/libs/bootstrap-rating/bootstrap-rating.css') }}" rel="stylesheet"
        type="text/css" />
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Quản lý bình luận</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Bình luận</li>
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
                    </div>

                    <div class="table-responsive min-vh-100">
                        <table class="table align-middle table-nowrap text-center dt-responsive nowrap w-100">
                            <thead class="">
                                <tr>
                                    <th>STT</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Product</th>
                                    <th>Content</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($comments as $comment)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            @if (!empty($comment->user->avatar) && Storage::exists($comment->user->avatar))
                                                <img src="{{ Storage::url($comment->user->avatar) }}"
                                                    alt="{{ $comment->user->name }}" width="30px" height="30px">
                                            @else
                                                <img src="https://laravel.com/img/logomark.min.svg" alt=""
                                                    style="height: 30px; width: 30px">
                                            @endif
                                        </td>

                                        <td>
                                            {{ $comment->user->name }}
                                        </td>

                                        <td>
                                            {{ limitTextLeng($comment->product->name, 20) }}
                                        </td>

                                        <td>
                                            {{ limitTextLeng($comment->content, 20, '***') }}
                                        </td>

                                        <td>
                                            <div class="rating-star">
                                                <input type="hidden" class="rating" data-filled="mdi mdi-star text-warning"
                                                    data-empty="mdi mdi-star-outline text-muted" data-readonly
                                                    value="{{ $comment->rating }}" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $comments->links() }}
                    </div>
                    
                    <!-- end table responsive -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/theme/admin/libs/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
    <script src="{{ asset('assets/theme/admin/js/pages/rating-init.js') }}"></script>
@endsection
