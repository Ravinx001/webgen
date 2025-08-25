/* ------------------------------------------------------------------------------
 *
 *  # Echarts - Area chart with zoom example
 *  Complaints Submitted vs Not Submitted
 *
 * ---------------------------------------------------------------------------- */

// Setup module
var EchartsAreaZoomLight = function() {

    // Area chart with zoom
    var _areaZoomLightExample = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }

        // Define element
        var area_zoom_element = document.getElementById('area_zoom');

        if (area_zoom_element) {

            // Initialize chart
            var area_zoom = echarts.init(area_zoom_element, null, { renderer: 'svg' });

            // Chart options
            area_zoom.setOption({

                // Colors
                color: ['#26A69A','#EF5350'],

                // Global text styles
                textStyle: {
                    fontFamily: 'var(--body-font-family)',
                    color: 'var(--body-color)',
                    fontSize: 14,
                    lineHeight: 22,
                    textBorderColor: 'transparent'
                },

                animationDuration: 750,

                grid: {
                    left: 0,
                    right: 40,
                    top: 35,
                    bottom: 60,
                    containLabel: true
                },

                // Legend
                legend: {
                    data: ['Submitted', 'Not Submitted'],
                    itemHeight: 8,
                    itemGap: 30,
                    textStyle: {
                        color: 'var(--body-color)'
                    }
                },

                // Tooltip
                tooltip: {
                    trigger: 'axis',
                    className: 'shadow-sm rounded',
                    backgroundColor: 'var(--white)',
                    borderColor: 'var(--gray-400)',
                    padding: 15,
                    textStyle: {
                        color: '#000'
                    }
                },

                // X Axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    axisLabel: { color: 'rgba(var(--body-color-rgb), .65)' },
                    axisLine: { lineStyle: { color: 'var(--gray-500)' } },
                    splitLine: { show: true, lineStyle: { color: 'var(--gray-300)' } },
                    data: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun']
                }],

                // Y Axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        formatter: '{value}',
                        color: 'rgba(var(--body-color-rgb), .65)'
                    },
                    axisLine: { show: true, lineStyle: { color: 'var(--gray-500)' } },
                    splitLine: { lineStyle: { color: 'var(--gray-300)' } },
                    splitArea: { show: true, areaStyle: { color: ['rgba(var(--white-rgb), .01)', 'rgba(var(--black-rgb), .01)'] } }
                }],

                // Zoom
                dataZoom: [
                    { type: 'inside', start: 30, end: 70 },
                    {
                        show: true,
                        type: 'slider',
                        start: 30,
                        end: 70,
                        height: 40,
                        bottom: 10,
                        borderColor: 'var(--gray-400)',
                        fillerColor: 'rgba(0,0,0,0.05)',
                        textStyle: { color: 'var(--body-color)' },
                        handleStyle: { color: '#8fb0f7', borderColor: 'rgba(0,0,0,0.25)' },
                        moveHandleStyle: { color: '#8fb0f7', borderColor: 'rgba(0,0,0,0.25)' }
                    }
                ],

                // Data series
                series: [
                    {
                        name: 'Submitted',
                        type: 'line',
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 8,
                        areaStyle: { normal: { opacity: 0.25 } },
                        data: [120, 200, 150, 220, 300, 250, 400]  // Example values
                    },
                    {
                        name: 'Not Submitted',
                        type: 'line',
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 8,
                        areaStyle: { normal: { opacity: 0.25 } },
                        data: [60, 100, 80, 120, 150, 100, 90]  // Example values
                    }
                ]
            });
        }

        // Resize handling
        var triggerChartResize = function() {
            area_zoom_element && area_zoom.resize();
        };

        var sidebarToggle = document.querySelectorAll('.sidebar-control');
        if (sidebarToggle) {
            sidebarToggle.forEach(function(togglers) {
                togglers.addEventListener('click', triggerChartResize);
            });
        }

        var resizeCharts;
        window.addEventListener('resize', function() {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        });
    };

    return {
        init: function() {
            _areaZoomLightExample();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function() {
    EchartsAreaZoomLight.init();
});
