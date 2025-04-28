@extends('admin.layouts.master')
@section('title', 'Danh sách mã giảm giá')

@section('style')
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/admin/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('theme/admin/assets/css/preloader.min.css') }}" type="text/css" />

    <link href="{{ asset('theme/admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('theme/admin/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        #datatable_length select {
            width: 60px;
        }

        #datatable thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection
@section('style-libs')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <h6 class="mb-sm-0 font-size-16">Danh sách mã giảm giá</h6>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-4">
                                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary me-2">+ Thêm mới</a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered w-100 text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã giảm giá</th>
                                    <th>Giảm giá</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Đơn hàng tối thiểu</th>
                                    <th>Số lần được sử dụng</th>
                                    <th>Số lần sử dụng tối đa</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($vouchers as $voucher)
                                <tr>
                                    <td>{{ $voucher->id }}</td>
                                    <td>{{ $voucher->code }}</td>
                                    <td>{{ number_format($voucher->discount, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ \Carbon\Carbon::parse($voucher->start_date_time)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($voucher->end_date_time)->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ number_format($voucher->min_order_amount) }}</td>
                                    <td>{{ $voucher->used_count }}</td>
                                    <td>{{ $voucher->max_usage }}</td>
                                    <td>
                                    <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}"
                                          class="form-check form-switch form-switch-success" 
                                          method="POST"
                                          id="toggleForm{{ $voucher->id }}">
                                        @csrf
                                        <input type="checkbox" 
                                               name="is_active" 
                                               id="switch{{ $voucher->id }}"
                                               class="form-check-input switch-is-active changeActive" 
                                               style="width: 55px; height: 25px;"
                                               {{ $voucher->is_active ? 'checked' : '' }}
                                               onchange="confirmChange({{ $voucher->id }}, this)">
                                        <label for="switch{{ $voucher->id }}" data-on-label="Yes" data-off-label="No"></label>
                                    </form>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.vouchers.show', $voucher)}}">
                                            <button title="Chi tiết" class="btn btn-success btn-sm" type="button">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </a>

                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}">
                                            <button title="Sửa" class="btn btn-warning btn-sm" type="button">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </a>

                                        {{-- <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher->id) }}" class="d-inline-block" >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Bạn có muốn xóa không')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function confirmChange(voucherId, checkbox) {
        // Lưu trạng thái ban đầu của checkbox
        const originalState = checkbox.checked;

        if (confirm("Bạn có muốn thay đổi trạng thái không?")) {
            // Nếu đồng ý, submit form
            document.getElementById('toggleForm' + voucherId).submit();
        } else {
            // Nếu hủy, khôi phục trạng thái ban đầu
            checkbox.checked = !originalState;
            return false;
        }
    }
    </script>
@endsection