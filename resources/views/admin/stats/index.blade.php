@extends('admin.layouts.master')

@section('title', 'GunDam DashBoard')

@section('style')
    <style>
        .nav-pills .nav-link {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Tổng doanh thu</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Thống kê</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    Bộ lọc từ ngày đến ngày...
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex flex-wrap">
                        <h4 class="card-title mb-4">Tổng doanh thu </h4>
                        <div class="ms-auto">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" data-time="day" onclick="updateChart('day')">Ngày</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-time="week" onclick="updateChart('week')">Tuần</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-time="month" onclick="updateChart('month')">Tháng</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div data-colors='["--bs-primary", "--bs-success", "--bs-warning", "--bs-info"]' dir="ltr" id="chart">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Dữ liệu từ PHP
        const data = {
            day: {
                labels: @json(array_keys($dailyData)),
                revenue: @json(array_values($dailyData))
            },
            week: {
                labels: @json(array_keys($weeklyData)),
                revenue: @json(array_values($weeklyData))
            },
            month: {
                labels: @json(array_keys($monthlyData)),
                revenue: @json(array_values($monthlyData))
            }
        };

        // Hàm lấy màu từ data-colors
        function getChartColorsArray(elementId) {
            const element = document.getElementById(elementId);
            if (!element) return null;

            const colors = JSON.parse(element.getAttribute("data-colors"));
            return colors.map(color => {
                color = color.replace(" ", "");
                if (color.indexOf(",") === -1) {
                    return getComputedStyle(document.documentElement).getPropertyValue(color) || color;
                }
                const [baseColor, opacity] = color.split(",");
                return `rgba(${getComputedStyle(document.documentElement).getPropertyValue(baseColor)},${opacity})`;
            });
        }

        // Cấu hình ban đầu
        const statisticsApplicationColors = getChartColorsArray("chart");
        let options = {
            series: [{
                name: "Doanh thu",
                type: "column",
                data: data.day.revenue
            }],
            chart: {
                height: 350,
                type: "bar",
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false,
                style: {
                    colors: ['#000000'],
                },
            },
            // border
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: data.day.labels,
                title: { text: 'Thời gian (Ngày)' }
            },
            yaxis: {
                title: { text: 'Doanh thu (VNĐ)' }
            },
            fill: { opacity: 1 },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toLocaleString() + " VNĐ";
                    }
                }
            },
            colors: statisticsApplicationColors
        };

        // Khởi tạo biểu đồ
        let chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Hàm cập nhật biểu đồ
        function updateChart(timeUnit) {
            const newData = data[timeUnit];
            let timeTitle;
            switch (timeUnit) {
                case 'day': timeTitle = 'Thời gian (Ngày)'; break;
                case 'week': timeTitle = 'Thời gian (Tuần)'; break;
                case 'month': timeTitle = 'Thời gian (Tháng)'; break;
            }

            chart.updateOptions({
                xaxis: {
                    categories: newData.labels,
                    title: { text: timeTitle }
                },
                series: [{ data: newData.revenue }]
            });

            // Cập nhật active class
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
                // if (link.textContent.toLowerCase() === timeUnit) {
                if (link.getAttribute('data-time') === timeUnit) {
                    link.classList.add('active');
                }
            });
        }
    </script>
@endsection