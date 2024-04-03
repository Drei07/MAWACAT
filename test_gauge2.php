<!DOCTYPE html>
<html>

<head>
    <title>ESP IOT DASHBOARD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="http://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.7/all/gauge.min.js"></script>
</head>
<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    }

    #pHLevel {
        width: 100%;
        height: 500px
    }

    #TDSLevel {
        width: 100%;
        height: 500px
    }
    #turbidity_Level {
        width: 100%;
        height: 500px
    }

    .am5-tooltip-container {
        display: none;
    }
</style>

<body>
    <div class="topnav">
        <h1 id="wifi_status">Device Status: Connecting........</h1>
    </div>
    <div id="pHLevel"></div>
    <div id="TDSLevel"></div>
    <div id="turbidity_Level"></div>



    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script>
        // PH LEVEL GAUGE
        var pHGauge = am5.Root.new("pHLevel");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        pHGauge.setThemes([
            am5themes_Animated.new(pHGauge)
        ]);


        // Create pHchart
        // https://www.amcharts.com/docs/v5/charts/radar-chart/
        var pHchart = pHGauge.container.children.push(am5radar.RadarChart.new(pHGauge, {
            panX: false,
            panY: false,
            startAngle: 160,
            endAngle: 380
        }));


        // Create axis and its renderer
        // https://www.amcharts.com/docs/v5/charts/radar-pHchart/gauge-charts/#Axes
        var pHaxisRenderer = am5radar.AxisRendererCircular.new(pHGauge, {
            innerRadius: -70
        });

        pHaxisRenderer.grid.template.setAll({
            stroke: pHGauge.interfaceColors.get("background"),
            visible: true,
            strokeOpacity: 0.8
        });

        var pHxAxis = pHchart.xAxes.push(am5xy.ValueAxis.new(pHGauge, {
            maxDeviation: 0,
            min: 0,
            max: 14,
            strictMinMax: true,
            renderer: pHaxisRenderer
        }));


        // Add clock hand
        var pHaxisDataItem = pHxAxis.makeDataItem({});

        var pHclockHand = am5radar.ClockHand.new(pHGauge, {
            pinRadius: am5.percent(20),
            radius: am5.percent(60),
            bottomWidth: 40
        })

        var pHbullet = pHaxisDataItem.set("bullet", am5xy.AxisBullet.new(pHGauge, {
            sprite: pHclockHand
        }));

        pHxAxis.createAxisRange(pHaxisDataItem);

        var pHlabel = pHchart.radarContainer.children.push(am5.Label.new(pHGauge, {
            fill: am5.color(0xffffff),
            centerX: am5.percent(50),
            textAlign: "center",
            centerY: am5.percent(50),
            fontSize: "2em"
        }));

        pHaxisDataItem.set("value", 0);
        pHbullet.get("sprite").on("rotation", function() {
            var value = pHaxisDataItem.get("value");
            var text = Math.round(pHaxisDataItem.get("value")).toString();
            var fill = am5.color(0x000000);
            pHxAxis.axisRanges.each(function(pHaxisRange) {
                if (value >= pHaxisRange.get("value") && value <= pHaxisRange.get("endValue")) {
                    fill = pHaxisRange.get("axisFill").get("fill");
                }
            })

            pHlabel.set("text", value.toFixed(2)); // Display with 2 decimal places

            pHclockHand.pin.animate({
                key: "fill",
                to: fill,
                duration: 500,
                easing: am5.ease.out(am5.ease.cubic)
            })
            pHclockHand.hand.animate({
                key: "fill",
                to: fill,
                duration: 500,
                easing: am5.ease.out(am5.ease.cubic)
            })
        });

        // Define variables for animation control
        var pHcurrentPhLevel = 0; // Current pH level displayed
        var pHtargetPhLevel = 0; // Target pH level for interpolation
        var pHanimationStartTime = performance.now(); // Timestamp to track animation start time
        var pHanimationDuration = 1000; // Duration for smooth animation (in milliseconds)

        // Function to update the gauge with smooth animation
        // Function to update the gauge with smooth animation
        function pHGaugeUpdate(pHLevel) {
            // Parse the new pH level as a floating-point number
            var parsedPhLevel = parseFloat(pHLevel);

            if (!isNaN(parsedPhLevel)) {
                pHtargetPhLevel = parsedPhLevel; // Set the new target pH level

                // Update the gauge smoothly using requestAnimationFrame
                function animate() {
                    var now = performance.now();
                    var progress = (now - pHanimationStartTime) / pHanimationDuration;

                    // Perform linear interpolation between current and target values
                    var interpolatedValue = pHcurrentPhLevel + (pHtargetPhLevel - pHcurrentPhLevel) * progress;

                    // Update the gauge with the interpolated value
                    // Use Number.toFixed(2) to format with two decimal places
                    pHaxisDataItem.set("value", Number(interpolatedValue.toFixed(2)));

                    // Continue animation until duration is reached
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        // Animation completed, update current value
                        pHcurrentPhLevel = pHtargetPhLevel;
                    }
                }

                // Start the animation
                pHanimationStartTime = performance.now();
                animate();
            } else {
                console.error('Invalid pH level received:', pHLevel);
            }
        }



        // Function to fetch data from server and update gauge
        function pHLevelFetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    var phLevel = data.phLevel;
                    pHGaugeUpdate(phLevel); // Update gauge smoothly with fetched pH level
                }
            };

            // Prepare JSON data to send in the POST request (if needed)
            var postData = JSON.stringify({});

            xhr.open('POST', 'arduino_connection.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(postData);
        }

        // Initial gauge update and periodic update using throttling
        pHLevelFetchData(); // Initial update
        setInterval(pHLevelFetchData, 2000); // Throttle updates to every 2 seconds



        // Create axis ranges bands
        var pHbandsData = [{
            title: "Acidic",
            color: "#f04922",
            lowScore: 0,
            highScore: 2
        }, {
            title: "Acidic",
            color: "#fdae19",
            lowScore: 2,
            highScore: 4
        }, {
            title: "Neutral",
            color: "#f3eb0c",
            lowScore: 4,
            highScore: 6
        }, {
            title: "Neutral",
            color: "#b0d136",
            lowScore: 6,
            highScore: 8
        }, {
            title: "Semi Neutral",
            color: "#6699ff",
            lowScore: 8,
            highScore: 10
        }, {
            title: "Alkaline",
            color: "#9999ff",
            lowScore: 10,
            highScore: 12
        }, {
            title: "Alkaline",
            color: "#cc66ff",
            lowScore: 12,
            highScore: 14
        }];

        am5.array.each(pHbandsData, function(data) {
            var pHaxisRange = pHxAxis.createAxisRange(pHxAxis.makeDataItem({}));

            pHaxisRange.setAll({
                value: data.lowScore,
                endValue: data.highScore
            });

            pHaxisRange.get("axisFill").setAll({
                visible: true,
                fill: am5.color(data.color),
                fillOpacity: 0.8
            });

            pHaxisRange.get("label").setAll({
                text: data.title,
                inside: true,
                radius: 15,
                fontSize: "1em",
                fill: pHGauge.interfaceColors.get("background")
            });
        });


        // Make stuff animate on load
        pHchart.appear(1000, 100);

        // --------------------------------------------------------------------------------------------TDS
        // TDS LEVEL GAUGE
        var TDSGauge = am5.Root.new("TDSLevel");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        TDSGauge.setThemes([
            am5themes_Animated.new(TDSGauge)
        ]);


        // Create TDSchart
        // https://www.amcharts.com/docs/v5/charts/radar-chart/
        var TDSchart = TDSGauge.container.children.push(am5radar.RadarChart.new(TDSGauge, {
            panX: false,
            panY: false,
            startAngle: 160,
            endAngle: 380
        }));


        // Create axis and its renderer
        // https://www.amcharts.com/docs/v5/charts/radar-TDSchart/gauge-charts/#Axes
        var TDSaxisRenderer = am5radar.AxisRendererCircular.new(TDSGauge, {
            innerRadius: -70
        });

        TDSaxisRenderer.grid.template.setAll({
            stroke: TDSGauge.interfaceColors.get("background"),
            visible: true,
            strokeOpacity: 0.8
        });

        var TDSxAxis = TDSchart.xAxes.push(am5xy.ValueAxis.new(TDSGauge, {
            maxDeviation: 0,
            min: 0,
            max: 2000,
            strictMinMax: true,
            renderer: TDSaxisRenderer
        }));


        // Add clock hand
        var TDSaxisDataItem = TDSxAxis.makeDataItem({});

        var TDSclockHand = am5radar.ClockHand.new(TDSGauge, {
            pinRadius: am5.percent(20),
            radius: am5.percent(60),
            bottomWidth: 40
        })

        var TDSbullet = TDSaxisDataItem.set("bullet", am5xy.AxisBullet.new(TDSGauge, {
            sprite: TDSclockHand
        }));

        TDSxAxis.createAxisRange(TDSaxisDataItem);

        var TDSlabel = TDSchart.radarContainer.children.push(am5.Label.new(TDSGauge, {
            fill: am5.color(0xffffff),
            centerX: am5.percent(50),
            textAlign: "center",
            centerY: am5.percent(50),
            fontSize: "2em"
        }));

        TDSaxisDataItem.set("value", 0);
        TDSbullet.get("sprite").on("rotation", function() {
            var value = TDSaxisDataItem.get("value");
            var text = Math.round(TDSaxisDataItem.get("value")).toString();
            var fill = am5.color(0x000000);
            TDSxAxis.axisRanges.each(function(TDSaxisRange) {
                if (value >= TDSaxisRange.get("value") && value <= TDSaxisRange.get("endValue")) {
                    fill = TDSaxisRange.get("axisFill").get("fill");
                }
            })

            TDSlabel.set("text", Math.round(value).toString());

            TDSclockHand.pin.animate({
                key: "fill",
                to: fill,
                duration: 500,
                easing: am5.ease.out(am5.ease.cubic)
            })
            TDSclockHand.hand.animate({
                key: "fill",
                to: fill,
                duration: 500,
                easing: am5.ease.out(am5.ease.cubic)
            })
        });

        // Define variables for animation control
        var TDScurrentPhLevel = 0; // Current TDS level displayed
        var TDStargetPhLevel = 0; // Target TDS level for interpolation
        var TDSanimationStartTime = performance.now(); // Timestamp to track animation start time
        var TDSanimationDuration = 1000; // Duration for smooth animation (in milliseconds)

        // Function to update the gauge with smooth animation
        // Function to update the gauge with smooth animation
        function TDSGaugeUpdate(TDSLevel) {
            // Parse the new TDS level as a floating-point number
            var parsedPhLevel = parseFloat(TDSLevel);

            if (!isNaN(parsedPhLevel)) {
                TDStargetPhLevel = parsedPhLevel; // Set the new target TDS level

                // Update the gauge smoothly using requestAnimationFrame
                function animate() {
                    var now = performance.now();
                    var progress = (now - TDSanimationStartTime) / TDSanimationDuration;

                    // Perform linear interpolation between current and target values
                    var interpolatedValue = TDScurrentPhLevel + (TDStargetPhLevel - TDScurrentPhLevel) * progress;

                    // Update the gauge with the interpolated value
                    // Use Number.toFixed(2) to format with two decimal places
                    TDSaxisDataItem.set("value", Number(interpolatedValue.toFixed(2)));

                    // Continue animation until duration is reached
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        // Animation completed, update current value
                        TDScurrentPhLevel = TDStargetPhLevel;
                    }
                }

                // Start the animation
                TDSanimationStartTime = performance.now();
                animate();
            } else {
                console.error('Invalid TDS level received:', TDSLevel);
            }
        }



        // Function to fetch data from server and update gauge
        function TDSLevelFetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    var TDSLevel = data.TDSLevel;
                    TDSGaugeUpdate(TDSLevel); // Update gauge smoothly with fetched TDS level
                }
            };

            // Prepare JSON data to send in the POST request (if needed)
            var postData = JSON.stringify({});

            xhr.open('POST', 'arduino_connection.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(postData);
        }

        // Initial gauge update and periodic update using throttling
        TDSLevelFetchData(); // Initial update
        setInterval(TDSLevelFetchData, 2000); // Throttle updates to every 2 seconds



        // Create axis ranges bands
        var TDSbandsData = [{
            title: "Acidic",
            color: "#f04922",
            lowScore: 0,
            highScore: 400
        }, {
            title: "Acidic",
            color: "#fdae19",
            lowScore: 400,
            highScore: 800
        }, {
            title: "Neutral",
            color: "#f3eb0c",
            lowScore: 800,
            highScore: 1200
        }, {
            title: "Neutral",
            color: "#b0d136",
            lowScore: 1200,
            highScore: 1600
        }, {
            title: "Semi Neutral",
            color: "#6699ff",
            lowScore: 1600,
            highScore: 2000
        }];

        am5.array.each(TDSbandsData, function(data) {
            var TDSaxisRange = TDSxAxis.createAxisRange(TDSxAxis.makeDataItem({}));

            TDSaxisRange.setAll({
                value: data.lowScore,
                endValue: data.highScore
            });

            TDSaxisRange.get("axisFill").setAll({
                visible: true,
                fill: am5.color(data.color),
                fillOpacity: 0.8
            });

            TDSaxisRange.get("label").setAll({
                text: data.title,
                inside: true,
                radius: 15,
                fontSize: "1em",
                fill: TDSGauge.interfaceColors.get("background")
            });
        });


        // Make stuff animate on load
        TDSchart.appear(1000, 100);


        // --------------------------------------------------------------------------------------------TURBIDTY

        // TURBIDTY LEVEL GAUGE
        var turbidity_Gauge = am5.Root.new("turbidity_Level");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        turbidity_Gauge.setThemes([
            am5themes_Animated.new(turbidity_Gauge)
        ]);


        // Create turbidity_chart
        // https://www.amcharts.com/docs/v5/charts/radar-chart/
        var turbidity_chart = turbidity_Gauge.container.children.push(am5radar.RadarChart.new(turbidity_Gauge, {
            panX: false,
            panY: false,
            startAngle: 160,
            endAngle: 380
        }));


        // Create axis and its renderer
        // https://www.amcharts.com/docs/v5/charts/radar-turbidity_chart/gauge-charts/#Axes
        var turbidity_axisRenderer = am5radar.AxisRendererCircular.new(turbidity_Gauge, {
            innerRadius: -70
        });

        turbidity_axisRenderer.grid.template.setAll({
            stroke: turbidity_Gauge.interfaceColors.get("background"),
            visible: true,
            strokeOpacity: 0.8
        });

        var turbidity_xAxis = turbidity_chart.xAxes.push(am5xy.ValueAxis.new(turbidity_Gauge, {
            maxDeviation: 0,
            min: 0,
            max: 100,
            strictMinMax: true,
            renderer: turbidity_axisRenderer
        }));


        // Add clock hand
        var turbidity_axisDataItem = turbidity_xAxis.makeDataItem({});

        var turbidity_clockHand = am5radar.ClockHand.new(turbidity_Gauge, {
            pinRadius: am5.percent(20),
            radius: am5.percent(60),
            bottomWidth: 40
        })

        var turbidity_bullet = turbidity_axisDataItem.set("bullet", am5xy.AxisBullet.new(turbidity_Gauge, {
            sprite: turbidity_clockHand
        }));

        turbidity_xAxis.createAxisRange(turbidity_axisDataItem);

        var turbidity_label = turbidity_chart.radarContainer.children.push(am5.Label.new(turbidity_Gauge, {
            fill: am5.color(0xffffff),
            centerX: am5.percent(50),
            textAlign: "center",
            centerY: am5.percent(50),
            fontSize: "2em"
        }));

        turbidity_axisDataItem.set("value", 0);
        turbidity_bullet.get("sprite").on("rotation", function() {
            var value = turbidity_axisDataItem.get("value");
            var text = Math.round(turbidity_axisDataItem.get("value")).toString();
            var fill = am5.color(0x000000);
            turbidity_xAxis.axisRanges.each(function(turbidity_axisRange) {
                if (value >= turbidity_axisRange.get("value") && value <= turbidity_axisRange.get("endValue")) {
                    fill = turbidity_axisRange.get("axisFill").get("fill");
                }
            })

            turbidity_label.set("text", Math.round(value).toString());

            turbidity_clockHand.pin.animate({
                key: "fill",
                to: fill,
                duration: 500,
                easing: am5.ease.out(am5.ease.cubic)
            })
            turbidity_clockHand.hand.animate({
                key: "fill",
                to: fill,
                duration: 500,
                easing: am5.ease.out(am5.ease.cubic)
            })
        });

        // Define variables for animation control
        var turbidity_currentPhLevel = 0; // Current turbidity_ level displayed
        var turbidity_targetPhLevel = 0; // Target turbidity_ level for interpolation
        var turbidity_animationStartTime = performance.now(); // Timestamp to track animation start time
        var turbidity_animationDuration = 1000; // Duration for smooth animation (in milliseconds)

        // Function to update the gauge with smooth animation
        // Function to update the gauge with smooth animation
        function turbidity_GaugeUpdate(turbidity_Level) {
            // Parse the new turbidity_ level as a floating-point number
            var parsedPhLevel = parseFloat(turbidity_Level);

            if (!isNaN(parsedPhLevel)) {
                turbidity_targetPhLevel = parsedPhLevel; // Set the new target turbidity_ level

                // Update the gauge smoothly using requestAnimationFrame
                function animate() {
                    var now = performance.now();
                    var progress = (now - turbidity_animationStartTime) / turbidity_animationDuration;

                    // Perform linear interpolation between current and target values
                    var interpolatedValue = turbidity_currentPhLevel + (turbidity_targetPhLevel - turbidity_currentPhLevel) * progress;

                    // Update the gauge with the interpolated value
                    // Use Number.toFixed(2) to format with two decimal places
                    turbidity_axisDataItem.set("value", Number(interpolatedValue.toFixed(2)));

                    // Continue animation until duration is reached
                    if (progress < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        // Animation completed, update current value
                        turbidity_currentPhLevel = turbidity_targetPhLevel;
                    }
                }

                // Start the animation
                turbidity_animationStartTime = performance.now();
                animate();
            } else {
                console.error('Invalid turbidity_ level received:', turbidity_Level);
            }
        }



        // Function to fetch data from server and update gauge
        function turbidity_LevelFetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    var turbidityLevel = data.turbidityLevel;
                    turbidity_GaugeUpdate(turbidityLevel); // Update gauge smoothly with fetched turbidity_ level
                }
            };

            // Prepare JSON data to send in the POST request (if needed)
            var postData = JSON.stringify({});

            xhr.open('POST', 'arduino_connection.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(postData);
        }

        // Initial gauge update and periodic update using throttling
        turbidity_LevelFetchData(); // Initial update
        setInterval(turbidity_LevelFetchData, 2000); // Throttle updates to every 2 seconds



        // Create axis ranges bands
        var turbidity_bandsData = [{
            title: "Acidic",
            color: "#f04922",
            lowScore: 0,
            highScore: 20
        }, {
            title: "Acidic",
            color: "#fdae19",
            lowScore: 20,
            highScore: 40
        }, {
            title: "Neutral",
            color: "#f3eb0c",
            lowScore: 40,
            highScore: 60
        }, {
            title: "Neutral",
            color: "#b0d136",
            lowScore: 60,
            highScore: 80
        }, {
            title: "Semi Neutral",
            color: "#6699ff",
            lowScore: 80,
            highScore: 100
        }];

        am5.array.each(turbidity_bandsData, function(data) {
            var turbidity_axisRange = turbidity_xAxis.createAxisRange(turbidity_xAxis.makeDataItem({}));

            turbidity_axisRange.setAll({
                value: data.lowScore,
                endValue: data.highScore
            });

            turbidity_axisRange.get("axisFill").setAll({
                visible: true,
                fill: am5.color(data.color),
                fillOpacity: 0.8
            });

            turbidity_axisRange.get("label").setAll({
                text: data.title,
                inside: true,
                radius: 15,
                fontSize: "1em",
                fill: turbidity_Gauge.interfaceColors.get("background")
            });
        });


        // Make stuff animate on load
        turbidity_chart.appear(1000, 100);
    </script>
</body>

</html>