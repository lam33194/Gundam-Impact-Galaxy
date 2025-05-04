// Function to get chart colors from data attributes
function getChartColorsArray(chartId) {
    if (document.getElementById(chartId) === null) {
        return null
    }

    const colors = document.getElementById(chartId).getAttribute("data-colors")
    if (!colors) {
        console.warn("data-colors Attribute not found on:", chartId)
        return null
    }

    return JSON.parse(colors).map((color) => {
        const trimmedColor = color.replace(" ", "")
        if (trimmedColor.indexOf(",") === -1) {
            const cssVar = getComputedStyle(document.documentElement).getPropertyValue(trimmedColor)
            return cssVar || trimmedColor
        }

        const colorParts = color.split(",")
        if (colorParts.length !== 2) return trimmedColor

        return (
            "rgba(" + getComputedStyle(document.documentElement).getPropertyValue(colorParts[0]) + "," + colorParts[1] + ")"
        )
    })
}

// Initialize the chart when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
    // Get colors for the chart
    const chartColors = getChartColorsArray("newOrdersChart")
    // if (chartColors) {
    // Create chart options
    // }

    // Extract counts and titles from orderChartData
    const counts = orderChartData.map(item => item.count);
    const titles = orderChartData.map(item => item.date);

    const orderOptions = {
        series: [
            {
                name: "New Orders",
                data: counts, // Use data passed from PHP
            },
        ],
        chart: {
            width: 130,
            height: 46,
            type: "area",
            sparkline: {
                enabled: true,
            },
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
            width: 1.5,
        },
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                inverseColors: false,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [50, 100, 100, 100],
            },
        },
        tooltip: {
            fixed: {
                enabled: true,
                position: 'topLeft',
                offsetX: -40,
                offsetY: -30,
            },
            x: {
                show: true,
                formatter: (seriesIndex, { dataPointIndex }) => {
                    return titles[dataPointIndex]; // Display the title for the hovered data point
                },
            },
            y: {
                formatter: (value) => value, // Display the count value
                title: {
                    formatter: () => "", // Remove the series name from tooltip
                },
            },
            marker: {
                show: false,
            },
        },
        colors: chartColors,
    }

    // Initialize the chart
    const newOrdersChart = new ApexCharts(document.querySelector("#newOrdersChart"), orderOptions)
    newOrdersChart.render()

    // Thống kê user ===================================================
    const userOptions = {
        series: [
            {
                name: 'Tổng chi',
                type: 'line',
                data: userChartData['total_spent']
            },
            {
                name: 'Tổng sản phẩm đã mua',
                type: 'column',
                data: userChartData['total_quantity']
            },
            {
                name: 'Tổng đơn hàng đã đặt',
                type: 'column',
                data: userChartData['total_orders']
            }
        ],
        chart: {
            height: 350,
            type: 'line',
            stacked: false
        },
        stroke: {
            width: [4, 1, 1]
        },
        title: {
            text: 'title',
            align: 'left',
            offsetX: 10
        },
        xaxis: {
            categories: userChartData['users'],
            title: {
                text: 'Khách hàng'
            },
        },
        yaxis: [
            {
                axisTicks: {
                    show: true
                },
                axisBorder: {
                    show: true,
                    color: '#008FFB'
                },
                labels: {
                    style: {
                        colors: '#008FFB',
                    },
                    formatter: function (val) {
                        return (val / 1000000).toFixed(2);
                    }
                },
                title: {
                    text: 'Tổng chi (Triệu)',
                },
            },
            {
                opposite: true,
                axisTicks: {
                    show: true
                },
                axisBorder: {
                    show: true,
                    color: '#00E396'
                },
                labels: {
                    style: {
                        colors: '#00E396'
                    }
                },
                title: {
                    text: 'Tổng sản phẩm',
                }
            },
            {
                opposite: true,
                axisTicks: {
                    show: true
                },
                axisBorder: {
                    show: true,
                    color: '#FEB019'
                },
                labels: {
                    style: {
                        colors: '#FEB019'
                    }
                },
                title: {
                    text: 'Tổng số đơn hàng',
                }
            }
        ],
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
        },
    };

    var topUserChart = new ApexCharts(document.querySelector("#topUserChart"), userOptions);
    topUserChart.render();

    // Thống kê product ===================================================

    const productOptions = {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'Số lượng bán',
            data: productChartData['total_sold']
        }],
        xaxis: {
            categories: productChartData['product_name'],
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
                fontFamily: 'Roboto, sans-serif',
            }
        },
        // colors: chartColors,
        colors: ['#00BFFF']
    };

    const topProductChart = new ApexCharts(document.querySelector("#topProductChart"), productOptions);
    topProductChart.render();

    // Lọc doanh thu ===================================================

    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    const form = document.getElementById('dateRangeForm');
    const fromDateFeedback = document.getElementById('fromDateFeedback');
    const toDateFeedback = document.getElementById('toDateFeedback');

    // Set max date to today for both inputs
    const today = new Date().toISOString().split('T')[0];
    fromDateInput.setAttribute('max', today);
    toDateInput.setAttribute('max', today);

    // Validate dates on input change
    fromDateInput.addEventListener('change', validateDates);
    toDateInput.addEventListener('change', validateDates);

    // Validate on form submit
    form.addEventListener('submit', function (e) {
        if (!validateDates()) {
            e.preventDefault();
        }
    });

    function validateDates() {
        const fromDate = fromDateInput.value;
        const toDate = toDateInput.value;
        let isValid = true;

        // Reset validation states
        fromDateInput.classList.remove('is-invalid');
        toDateInput.classList.remove('is-invalid');
        fromDateFeedback.style.display = 'none';
        toDateFeedback.style.display = 'none';

        // Check if both dates are selected
        if (fromDate && toDate) {
            // Compare dates
            if (fromDate > toDate) {
                toDateInput.classList.add('is-invalid');
                toDateFeedback.style.display = 'block';
                isValid = false;
            }
        }

        return isValid;
    }
})

// Thống kê doanh thu ===================================================

let options = {
    series: [{
        name: "Doanh thu",
        type: "column",
        data: revenueChartData.day.revenue
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
    stroke: { show: true, width: 2, colors: ['transparent'] },
    xaxis: {
        categories: revenueChartData.day.labels,
        title: { text: 'Thời gian (Ngày)' }
    },
    yaxis: {
        title: {
            text: 'Doanh thu (VNĐ)',
            offsetX: 5,
        }
    },
    fill: { opacity: 1 },
    tooltip: {
        y: {
            formatter: function (val) {
                return val.toLocaleString() + " VNĐ";
            }
        }
    },
    colors: ['#556EE6'],
    title: {
        text: new Intl.NumberFormat('vi-VN').format(revenueChartData.day.total) + ' VNĐ'
    }
};

// Khởi tạo biểu đồ
const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), options);
revenueChart.render();

// Hàm cập nhật biểu đồ
function updateChart(timeUnit) {
    const newData = revenueChartData[timeUnit];

    let timeTitle;

    switch (timeUnit) {
        case 'day': {
            timeTitle = 'Thời gian (Ngày)';
            break;
        }

        case 'week': {
            timeTitle = 'Thời gian (Tuần)';
            break;
        }

        case 'month': {
            timeTitle = 'Thời gian (Tháng)';
            break;
        }

        case 'year': {
            timeTitle = 'Thời gian (Năm)';
            break;
        }
    }

    revenueChart.updateOptions({
        xaxis: {
            categories: newData.labels,
            title: { text: timeTitle }
        },
        series: [{ data: newData.revenue }],
        title: {
            // 
        }
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
