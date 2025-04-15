@extends('admin.layouts.master')
@section('title', 'GunDam DashBoard')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card p-3 text-center shadow">
            <h5>Khách hàng mới (7 ngày)</h5>
            <h2 id="newCustomers">--</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center shadow">
            <h5>Ngày cao điểm</h5>
            <div id="peakDay">--</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center shadow">
            <h5>Trung bình mỗi ngày</h5>
            <h2 id="avgPerDay">--</h2>
        </div>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">
        <h5>Top 5 khách hàng mua nhiều nhất</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                    <th>Tổng sản phẩm đã mua</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCustomers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->total_products }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Khách hàng mới trong 7 ngày</h4>
        <canvas id="customerChart" height="100"></canvas>
    </div>
</div>

@endsection
@section('script')

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Dữ liệu mẫu -  sẽ thay bằng dữ liệu thật từ controller
    const stats = {
        newCustomers: 11,
        peakDay: {
            date: '12/04/2025',
            total: 11
        },
        avgPerDay: 1.6
    };

    // Gán dữ liệu vào giao diện
    document.getElementById('newCustomers').innerText = stats.newCustomers;
    document.getElementById('peakDay').innerText = `${stats.peakDay.date} (${stats.peakDay.total} KH)`;
    document.getElementById('avgPerDay').innerText = stats.avgPerDay;
});
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('customerChart').getContext('2d');
    const customerChart = new Chart(ctx, {
        type: 'bar', // đổi sang 'line' nếu muốn
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Khách hàng mới',
                data: @json($data),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush

</script>
    
@endsection