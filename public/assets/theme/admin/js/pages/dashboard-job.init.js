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

    
})