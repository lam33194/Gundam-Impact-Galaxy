@extends('admin.layouts.master')
@section('title', 'Thống kê sản phẩm')
@section('content')
    <div class="container">
        <h3>Thống kê sản phẩm bán chạy</h3>

        <form method="GET" action="{{ route('admin.product_statistics.index') }}" class="mb-4">
            <div style="margin-bottom: 10px;">
                <label>Từ ngày:
                    <input type="date" name="start" value="{{ $start }}">
                </label>
                <label>Đến ngày:
                    <input type="date" name="end" value="{{ $end }}">
                </label>
                <label>Số sản phẩm:
                    <select name="limit">
                        <option value="5" {{ $limit == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $limit == 10 ? 'selected' : '' }}>10</option>
                    </select>
                </label>
                <button type="submit">Xem thống kê</button>
            </div>
        </form>

        {{-- Bảng thống kê --}}
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr style="background: #f0f0f0;">
                    <th>#</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng đã bán</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->total_sold }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Không có sản phẩm nào được bán trong khoảng thời gian này.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Biểu đồ --}}
        <div id="barchart" style="margin-top: 40px;"></div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const productNames = {!! json_encode($products->pluck('product_name')) !!};
            const totalSold = {!! json_encode($products->pluck('total_sold')) !!};

            if (productNames.length > 0 && totalSold.length > 0) {
                const options = {
                    chart: {
                        type: 'bar',
                        height: 400
                    },
                    series: [{
                        name: 'Số lượng bán',
                        data: totalSold
                    }],
                    xaxis: {
                        categories: productNames,
                        labels: {
                            style: {
                                fontSize: '14px'
                            }
                        }
                    },
                    title: {
                        text: 'Biểu đồ sản phẩm bán chạy',
                        align: 'center',
                        style: {
                            fontSize: '20px'
                        }
                    },
                    colors: ['#00BFFF']
                };

                const chart = new ApexCharts(document.querySelector("#barchart"), options);
                chart.render();
            }
        });
    </script>
@endsection
