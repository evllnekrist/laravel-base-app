<script type="text/javascript">
    $(window).on("load", function () {

        var $primary = '#7367F0';
        var $success = '#28C76F';
        var $danger = '#EA5455';
        var $warning = '#FF9F43';
        var $info = '#00cfe8';
        var $primary_light = '#A9A2F6';
        var $danger_light = '#f29292';
        var $success_light = '#55DD92';
        var $warning_light = '#ffc085';
        var $info_light = '#1fcadb';
        var $strok_color = '#b9c3cd';
        var $label_color = '#e7e7e7';
        var $white = '#fff';


        // Line Area Chart - 1
        // ----------------------------------
            var gainedlineChartoptions = {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                    sparkline: {
                        enabled: true
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    },
                },
                colors: [$info],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.9,
                        opacityFrom: 0.7,
                        opacityTo: 0.5,
                        stops: [0, 80, 100]
                    }
                },
                series: [{
                    name: 'Member',
                    data: $("#line-area-chart-1").data("value")
                }],

                xaxis: {
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                    categories: [
                        "Jan "+new Date().getFullYear(),
                        "Feb "+new Date().getFullYear(),
                        "Mar "+new Date().getFullYear(),
                        "Apr "+new Date().getFullYear(),
                        "May "+new Date().getFullYear(),
                        "Jun "+new Date().getFullYear(),
                        "Jul "+new Date().getFullYear(),
                        "Aug "+new Date().getFullYear(),
                        "Sep "+new Date().getFullYear(),
                        "Oct "+new Date().getFullYear(),
                        "Nov "+new Date().getFullYear(),
                        "Dec "+new Date().getFullYear()
                    ]
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: { left: 0, right: 0 },
                }],
                tooltip: {
                    x: { show: true }
                },
            }
            var gainedlineChart = new ApexCharts(
                document.querySelector("#line-area-chart-1"),
                gainedlineChartoptions
            );
            gainedlineChart.render();
        // ----------------------------------LAC-1----||----end

        // Line Area Chart - 2
        // ----------------------------------
            var revenuelineChartoptions = {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                    sparkline: {
                        enabled: true
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    },
                },
                colors: [$success],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                    }
                },
                series: [{
                    name: 'Staff',
                    data: $("#line-area-chart-2").data("value")
                }],

                xaxis: {
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                    categories: [
                        "Jan "+new Date().getFullYear(),
                        "Feb "+new Date().getFullYear(),
                        "Mar "+new Date().getFullYear(),
                        "Apr "+new Date().getFullYear(),
                        "May "+new Date().getFullYear(),
                        "Jun "+new Date().getFullYear(),
                        "Jul "+new Date().getFullYear(),
                        "Aug "+new Date().getFullYear(),
                        "Sep "+new Date().getFullYear(),
                        "Oct "+new Date().getFullYear(),
                        "Nov "+new Date().getFullYear(),
                        "Dec "+new Date().getFullYear()
                    ]
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: { left: 0, right: 0 },
                }],
                tooltip: {
                    x: { show: true }
                },
            }
            var revenuelineChart = new ApexCharts(
                document.querySelector("#line-area-chart-2"),
                revenuelineChartoptions
            );
            revenuelineChart.render();
        // ----------------------------------LAC-2----||----end

        // Line Area Chart - 3
        // ----------------------------------
            var saleslineChartoptions = {
                chart: {
                    height: 100,
                    type: 'area',
                    toolbar: {
                        show: false,
                    },
                    sparkline: {
                        enabled: true
                    },
                    grid: {
                        show: false,
                        padding: {
                            left: 0,
                            right: 0
                        }
                    },
                },
                colors: [$danger],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.9,
                        opacityFrom: 0.7,
                        opacityTo: 0.5,
                        stops: [0, 80, 100]
                    }
                },
                series: [{
                    name: 'PT',
                    data: $("#line-area-chart-3").data("value")
                }],

                xaxis: {
                    labels: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                    categories: [
                        "Jan "+new Date().getFullYear(),
                        "Feb "+new Date().getFullYear(),
                        "Mar "+new Date().getFullYear(),
                        "Apr "+new Date().getFullYear(),
                        "May "+new Date().getFullYear(),
                        "Jun "+new Date().getFullYear(),
                        "Jul "+new Date().getFullYear(),
                        "Aug "+new Date().getFullYear(),
                        "Sep "+new Date().getFullYear(),
                        "Oct "+new Date().getFullYear(),
                        "Nov "+new Date().getFullYear(),
                        "Dec "+new Date().getFullYear()
                    ]
                },
                yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: { left: 0, right: 0 },
                }],
                tooltip: {
                    x: { show: true }
                },
            }
            var saleslineChart = new ApexCharts(
                document.querySelector("#line-area-chart-3"),
                saleslineChartoptions
            );
            saleslineChart.render();
        // ----------------------------------LAC-3----||----end

        // Today Absence Chart
        // -----------------------------
            var goalChartoptions = {
                chart: {
                    height: 250,
                    type: 'radialBar',
                    sparkline: {
                        enabled: true,
                    },
                    dropShadow: {
                        enabled: true,
                        blur: 3,
                        left: 1,
                        top: 1,
                        opacity: 0.1
                    },
                },
                colors: [$success],
                plotOptions: {
                    radialBar: {
                        size: 110,
                        startAngle: -150,
                        endAngle: 150,
                        hollow: {
                            size: '77%',
                        },
                        track: {
                            background: $strok_color,
                            strokeWidth: '50%',
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                offsetY: 18,
                                color: '#99a2ac',
                                fontSize: '4rem'
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                    shade: 'dark',
                    type: 'horizontal',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#00b5b5'],
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                    },
                },
                series: [$("#goal-overview-chart").data("value")],
                stroke: {
                    lineCap: 'round'
                },
            }
            var goalChart = new ApexCharts(
                document.querySelector("#goal-overview-chart"),
                goalChartoptions
            );
            goalChart.render();
        // ----------------------------------Absence----||----end

        // Subscription Chart
        // ----------------------------------
            var sessionChartoptions = {
                chart: {
                    type: 'pie',
                    height: 325,
                    toolbar: {
                    show: false
                    }
                },
                dataLabels: {
                    enabled: true
                },
                series: $("#session-chart").data("value"),
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    showForNullSeries: true,
                    showForZeroSeries: true,
                },
                labels: ['expired','within subscription'],
                stroke: { width: 0 },
                colors: [$danger,$success],
                fill: {
                    type: 'gradient',
                    gradient: {
                    gradientToColors: [$danger_light,$success_light]
                    }
                }
            }
            var sessionChart = new ApexCharts(
                document.querySelector("#session-chart"),
                sessionChartoptions
            );
            sessionChart.render();
        // ----------------------------------Subs----||----end

    });

</script>