<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }

    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<body>
    <div id="chartdiv"></div>


</body>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    /**
     * ---------------------------------------
     * This demo was created using amCharts 5.
     * 
     * For more information visit:
     * https://www.amcharts.com/
     * 
     * Documentation is available at:
     * https://www.amcharts.com/docs/v5/
     * ---------------------------------------
     */


    // Create root element
    // https://www.amcharts.com/docs/v5/getting-started/#Root_element
    var root = am5.Root.new("chartdiv");

    // Set themes
    // https://www.amcharts.com/docs/v5/concepts/themes/
    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    // Create chart
    // https://www.amcharts.com/docs/v5/charts/xy-chart/
    var chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            focusable: true,
            panX: true,
            panY: true,
            wheelX: "panX",
            wheelY: "zoomX",
            pinchZoomX: true
        })
    );

    var easing = am5.ease.linear;
    chart.get("colors").set("step", 4);

    // Create axes
    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    var xAxis = chart.xAxes.push(
        am5xy.DateAxis.new(root, {
            maxDeviation: 0.1,
            groupData: false,
            baseInterval: {
                timeUnit: "day",
                count: 1
            },
            renderer: am5xy.AxisRendererX.new(root, {
                minGridDistance: 80,
                minorGridEnabled: true
            }),
            tooltip: am5.Tooltip.new(root, {})
        })
    );

    function createAxisAndSeries(startValue, opposite) {
        var yRenderer = am5xy.AxisRendererY.new(root, {
            opposite: opposite
        });
        var yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
                maxDeviation: 1,
                renderer: yRenderer
            })
        );

        if (chart.yAxes.indexOf(yAxis) > 0) {
            yAxis.set("syncWithAxis", chart.yAxes.getIndex(0));
        }

        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(
            am5xy.LineSeries.new(root, {
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                valueXField: "date",
                tooltip: am5.Tooltip.new(root, {
                    pointerOrientation: "horizontal",
                    labelText: "{valueY}"
                })
            })
        );

        //series.fills.template.setAll({ fillOpacity: 0.2, visible: true });
        series.strokes.template.setAll({
            strokeWidth: 1
        });

        yRenderer.grid.template.set("strokeOpacity", 1);
        yRenderer.labels.template.set("fill", series.get("fill"));
        yRenderer.setAll({
            stroke: series.get("fill"),
            strokeOpacity: 1,
            opacity: 1
        });

        // Set up data processor to parse string dates
        // https://www.amcharts.com/docs/v5/concepts/data/#Pre_processing_data
        series.data.processor = am5.DataProcessor.new(root, {
            dateFormat: "yyyy-MM-dd",
            dateFields: ["date"]
        });

        series.data.setAll(generateChartData(startValue));
    }

    // Add cursor
    // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
        xAxis: xAxis,
        behavior: "none"
    }));
    cursor.lineY.set("visible", false);

    // add scrollbar
    chart.set("scrollbarX", am5.Scrollbar.new(root, {
        orientation: "horizontal"
    }));

    createAxisAndSeries(100, false);
    createAxisAndSeries(1000, true);
    createAxisAndSeries(8000, true);

    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    chart.appear(1000, 100);

    // Generates random data, quite different range
    function generateChartData(value) {
        var data = [];
        var firstDate = new Date();
        firstDate.setDate(firstDate.getDate() - 100);
        firstDate.setHours(0, 0, 0, 0);

        for (var i = 0; i < 100; i++) {
            var newDate = new Date(firstDate);
            newDate.setDate(newDate.getDate() + i);

            value += Math.round(
                ((Math.random() < 0.5 ? 1 : -1) * Math.random() * value) / 20
            );

            data.push({
                date: newDate,
                value: value
            });
        }
        return data;
    }
</script>

</html>

