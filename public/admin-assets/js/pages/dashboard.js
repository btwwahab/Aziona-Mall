/**
* Theme: Larkon - Responsive Bootstrap 5 Admin Dashboard
* Author: Techzaa
* Module/App: Dashboard
*/

//
// Conversions
// 
var options = {
    chart: {
        height: 292,
        type: 'radialBar',
    },
    plotOptions: {
        radialBar: {
            startAngle: -135,
            endAngle: 135,
            dataLabels: {
                name: {
                    fontSize: '14px',
                    color: "undefined",
                    offsetY: 100
                },
                value: {
                    offsetY: 55,
                    fontSize: '20px',
                    color: undefined,
                    formatter: function (val) {
                        return val + "%";
                    }
                }
            },
            track: {
                background: "rgba(170,184,197, 0.2)",
                margin: 0
            },
        }
    },
    fill: {
        gradient: {
            enabled: true,
            shade: 'dark',
            shadeIntensity: 0.2,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
        },
    },
    stroke: {
        dashArray: 4
    },
    colors: ["#088179", "#22c55e"],
    series: [65.2],
    labels: ['Returning Customer'],
    responsive: [{
        breakpoint: 380,
        options: {
            chart: {
                height: 180
            }
        }
    }],
    grid: {
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        }
    }
}

var chart = new ApexCharts(
    document.querySelector("#conversions"),
    options
);

chart.render();


//
//Performance-chart
//
document.addEventListener("DOMContentLoaded", function () {
    var fullData = window.chartOrdersData || {};

    const confirmedData = fullData.confirmed || Array(12).fill(0);
    const pendingData = fullData.pending || Array(12).fill(0);
    const rejectedData = fullData.rejected || Array(12).fill(0);

    let chart = new ApexCharts(
        document.querySelector("#dash-performance-chart"),
        getChartOptions(confirmedData, pendingData, rejectedData)
    );

    chart.render();

    // Filter buttons
    document.getElementById('btnAll').addEventListener('click', function () {
        updateChart(confirmedData, pendingData, rejectedData);
        setActive(this);
    });

    document.getElementById('btn1M').addEventListener('click', function () {
        updateChart(
            confirmedData.slice(-1),
            pendingData.slice(-1),
            rejectedData.slice(-1)
        );
        setActive(this);
    });

    document.getElementById('btn6M').addEventListener('click', function () {
        updateChart(
            confirmedData.slice(-6),
            pendingData.slice(-6),
            rejectedData.slice(-6)
        );
        setActive(this);
    });

    document.getElementById('btn1Y').addEventListener('click', function () {
        updateChart(confirmedData, pendingData, rejectedData);
        setActive(this);
    });

    function updateChart(confirmed, pending, rejected) {
        chart.updateSeries([
            { name: "Confirmed Orders", type: "bar", data: confirmed },
            { name: "Pending Orders", type: "area", data: pending },
            { name: "Rejected Orders", type: "line", data: rejected }
        ]);
    }

    function setActive(button) {
        document.querySelectorAll('button[id^="btn"]').forEach(btn =>
            btn.classList.remove('active')
        );
        button.classList.add('active');
    }

    function getChartOptions(confirmed, pending, rejected) {
        return {
            series: [
                { name: "Confirmed Orders", type: "bar", data: confirmed },
                { name: "Pending Orders", type: "area", data: pending },
                { name: "Rejected Orders", type: "line", data: rejected }
            ],
            chart: {
                height: 313,
                type: "line",
                toolbar: { show: false },
            },
            stroke: {
                dashArray: [0, 2, 5],
                width: [0, 2, 2],
                curve: 'smooth'
            },
            fill: {
                opacity: [1, 0.25, 1],
                type: ['solid', 'gradient', 'solid'],
                gradient: {
                    type: "vertical",
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    stops: [0, 90]
                },
            },
            markers: { size: [0, 0, 4], strokeWidth: 2, hover: { size: 6 } },
            xaxis: {
                categories: [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ],
                axisTicks: { show: false },
                axisBorder: { show: false },
            },
            yaxis: {
                min: 0,
                axisBorder: { show: false },
            },
            grid: {
                show: true,
                strokeDashArray: 3,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: -2, bottom: 0, left: 10 },
            },
            legend: {
                show: true,
                horizontalAlign: "center",
                offsetX: 0,
                offsetY: 5,
                markers: { width: 9, height: 9, radius: 6 },
                itemMargin: { horizontal: 10, vertical: 0 },
            },
            plotOptions: {
                bar: {
                    columnWidth: "30%",
                    borderRadius: 3,
                },
            },
            colors: ["#088179", "#f59e0b", "#ef4444"],
            tooltip: { shared: true }
        };
    }
});







class VectorMap {


    initWorldMapMarker() {
        const map = new jsVectorMap({
            map: 'world',
            selector: '#world-map-markers',
            zoomOnScroll: true,
            zoomButtons: false,
            markersSelectable: true,
            markers: [
                { name: "Canada", coords: [56.1304, -106.3468] },
                { name: "Brazil", coords: [-14.2350, -51.9253] },
                { name: "Russia", coords: [61, 105] },
                { name: "China", coords: [35.8617, 104.1954] },
                { name: "United States", coords: [37.0902, -95.7129] }
            ],
            markerStyle: {
                initial: { fill: "#7f56da" },
                selected: { fill: "#22c55e" }
            },
            labels: {
                markers: {
                    render: marker => marker.name
                }
            },
            regionStyle: {
                initial: {
                    fill: 'rgba(169,183,197, 0.3)',
                    fillOpacity: 1,
                },
            },
        });
    }

    init() {
        this.initWorldMapMarker();
    }

}

document.addEventListener('DOMContentLoaded', function (e) {
    new VectorMap().init();
});