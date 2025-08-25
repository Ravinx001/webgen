/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Line chart with zoom example
 *
 *  Demo JS code for line chart with zoom option [light theme]
 *
 * ---------------------------------------------------------------------------- */

// Setup module
// ------------------------------

var EchartsLinesZoomLight = (function () {
    //
    // Setup module components
    //

    // Line chart with zoom
    var _linesZoomLightExample = function () {
        if (typeof echarts == "undefined") {
            console.warn("Warning - echarts.min.js is not loaded.");
            return;
        }

        // Define element
        var line_zoom_element = document.getElementById("line_zoom");

        //
        // Charts configuration
        //

        if (line_zoom_element) {
            // Initialize chart
            var line_zoom = echarts.init(line_zoom_element, null, {
                renderer: "svg",
            });

            //
            // Chart config
            //

            // Options
            line_zoom.setOption({
                // Define colors
                color: ["#4fc686", "#d74e67", "#0092ff"],

                // Global text styles
                textStyle: {
                    fontFamily: "var(--body-font-family)",
                    color: "var(--body-color)",
                    fontSize: 14,
                    lineHeight: 22,
                    textBorderColor: "transparent",
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 10,
                    right: 30,
                    top: 35,
                    bottom: 60,
                    containLabel: true,
                },

                // Add legend
                legend: {
                    data: [
                        "Health",
                        "Transport",
                        "Education",
                        "Civil",
                        "Media",
                        "Law & Order",
                        "Environment",
                        "Finance",
                    ],
                    itemHeight: 8,
                    itemGap: 30,
                    textStyle: {
                        color: "var(--body-color)",
                    },
                },

                // Add tooltip
                tooltip: {
                    trigger: "axis",
                    className: "shadow-sm rounded",
                    backgroundColor: "var(--white)",
                    borderColor: "var(--gray-400)",
                    padding: 15,
                    textStyle: {
                        color: "#000",
                    },
                },

                // Horizontal axis
                xAxis: [
                    {
                        type: "category",
                        boundaryGap: false,
                        axisLabel: {
                            color: "rgba(var(--body-color-rgb), .65)",
                        },
                        axisLine: {
                            lineStyle: {
                                color: "var(--gray-500)",
                            },
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: "var(--gray-300)",
                            },
                        },
                        data: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                        ],
                    },
                ],

                // Vertical axis
                yAxis: [
                    {
                        type: "value",
                        axisLabel: {
                            formatter: "{value} ",
                            color: "rgba(var(--body-color-rgb), .65)",
                        },
                        axisLine: {
                            show: true,
                            lineStyle: {
                                color: "var(--gray-500)",
                            },
                        },
                        splitLine: {
                            lineStyle: {
                                color: "var(--gray-300)",
                            },
                        },
                        splitArea: {
                            show: true,
                            areaStyle: {
                                color: [
                                    "rgba(var(--white-rgb), .01)",
                                    "rgba(var(--black-rgb), .01)",
                                ],
                            },
                        },
                    },
                ],

                // Zoom control
                dataZoom: [
                    {
                        type: "inside",
                        start: 30,
                        end: 70,
                    },
                    {
                        show: true,
                        type: "slider",
                        start: 30,
                        end: 70,
                        height: 40,
                        bottom: 10,
                        borderColor: "var(--gray-400)",
                        fillerColor: "rgba(0,0,0,0.05)",
                        textStyle: {
                            color: "var(--body-color)",
                        },
                        handleStyle: {
                            color: "#8fb0f7",
                            borderColor: "rgba(0,0,0,0.25)",
                        },
                        moveHandleStyle: {
                            color: "#8fb0f7",
                            borderColor: "rgba(0,0,0,0.25)",
                        },
                        dataBackground: {
                            lineStyle: {
                                color: "var(--gray-500)",
                            },
                            areaStyle: {
                                color: "var(--gray-500)",
                                opacity: 0.1,
                            },
                        },
                    },
                ],

                // Add series
                series: [
                    {
                        name: "Health",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [30, 40, 35, 50, 60, 55, 70, 65],
                    },
                    {
                        name: "Transport",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [20, 25, 30, 40, 35, 45, 50, 55],
                    },
                    {
                        name: "Education",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [15, 20, 18, 25, 30, 28, 35, 40],
                    },
                    {
                        name: "Civil",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [10, 12, 15, 20, 18, 25, 22, 30],
                    },
                    {
                        name: "Media",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [5, 8, 10, 12, 15, 18, 20, 25],
                    },
                    {
                        name: "Law & Order",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [12, 15, 18, 22, 25, 30, 28, 35],
                    },
                    {
                        name: "Environment",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [8, 10, 12, 15, 18, 20, 22, 25],
                    },
                    {
                        name: "Finance",
                        type: "line",
                        smooth: true,
                        symbol: "circle",
                        symbolSize: 8,
                        data: [18, 20, 22, 25, 28, 30, 32, 35],
                    },
                ],
            });
        }

        //
        // Resize charts
        //

        // Resize function
        var triggerChartResize = function () {
            line_zoom_element && line_zoom.resize();
        };

        // On sidebar width change
        var sidebarToggle = document.querySelectorAll(".sidebar-control");
        if (sidebarToggle) {
            sidebarToggle.forEach(function (togglers) {
                togglers.addEventListener("click", triggerChartResize);
            });
        }

        // On window resize
        var resizeCharts;
        window.addEventListener("resize", function () {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };

    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _linesZoomLightExample();
        },
    };
})();

// Initialize module
// ------------------------------

document.addEventListener("DOMContentLoaded", function () {
    EchartsLinesZoomLight.init();
});
