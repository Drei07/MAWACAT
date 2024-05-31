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
        innerRadius: -40
    });

    pHaxisRenderer.grid.template.setAll({
        stroke: pHGauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1,

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
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
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
        fontSize: "1.3em"
    }));

    pHaxisDataItem.set("value", 0);
    pHbullet.get("sprite").on("rotation", function () {
        var value = pHaxisDataItem.get("value");
        var text = Math.round(pHaxisDataItem.get("value")).toString();
        var fill = am5.color(0x000000);
        pHxAxis.axisRanges.each(function (pHaxisRange) {
            if (value >= pHaxisRange.get("value") && value <= pHaxisRange.get("endValue")) {
                fill = pHaxisRange.get("axisFill").get("fill");
            }
        })

        pHlabel.set("text", value.toFixed(2).toString());


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
                document.getElementById('phValue').value = (interpolatedValue.toFixed(2));
                document.getElementById('pHlevel1').innerText = (interpolatedValue.toFixed(2)) + ' pH';



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


    function pHsetupWiFiStatusCheckAndEnableMonitoring() {
        // This function checks the WiFi status
        function updateWifiStatus() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById('wifi_status').innerText = data.wifi_status;

                    if (data.wifi_status === 'Connected' ) {
                        document.getElementById('startBtn').style.display = 'inline-block';
                        document.getElementById('wifiLoadIcon').style.display = "none";
                    } else {
                        document.getElementById('startBtn').style.display = 'none';
                    }
                }
            };
            xhr.open('GET', 'controller/arduino-controller.php', true);
            xhr.send();
        }

        // This function starts the pH monitoring process
        function startMonitoring() {
            document.getElementById('startBtn').style.display = 'none';                        
            document.getElementById('analyzingIcon').style.display = "inline-block";


            var startTime = Date.now(); // Record the start time
            var updateInterval; // To hold the interval for updating the pH level
   
            function stopUpdating() {
                clearInterval(updateInterval); // Stop the interval
                console.log('Stopped updating pH level after 30 seconds.');

                    // Perform data analysis
                var sensorValues = {
                    ph: phLevel,
                    tds: TDSLevel,
                    turbidity: turbidityLevel,
                    temperature: temperatureLevel
                };
                
                var report = generateReport(sensorValues);
                console.log(report);
                document.getElementById('report').innerText = report;

                document.getElementById('analyzingIcon').style.display = "none";
                document.getElementById('restartBtn').style.display = 'inline-block';                        
                document.getElementById('saveBtn').style.display = 'inline-block';                    
            }

            // Function to generate the report based on sensor values
            function generateReport(sensorValues) {
                
                var report = "Sensor Analysis Report:\n";
                var allWithinLimits = true;

                // Check pH value
                if (sensorValues.ph < phLow || sensorValues.ph > phHigh) {
                    report += `pH value ${sensorValues.ph} is out of range (${phLow}-${phHigh}).\n`;
                    allWithinLimits = false;
                } else {
                    report += `pH value ${sensorValues.ph} is within range.\n`;
                }

                // Check TDS value
                if (sensorValues.tds < tdsLow || sensorValues.tds > tdsHigh) {
                    report += `TDS value ${sensorValues.tds} is out of range (${tdsLow}-${tdsHigh}).\n`;
                    allWithinLimits = false;
                } else {
                    report += `TDS value ${sensorValues.tds} is within range.\n`;
                }

                // Check turbidity value
                if (sensorValues.turbidity < turbidityLow || sensorValues.turbidity > turbidityHigh) {
                    report += `Turbidity value ${sensorValues.turbidity} is out of range (${turbidityLow}-${turbidityHigh}).\n`;
                    allWithinLimits = false;
                } else {
                    report += `Turbidity value ${sensorValues.turbidity} is within range.\n`;
                }

                // Check temperature value
                if (sensorValues.temperature < temperatureLow || sensorValues.temperature > temperatureHigh) {
                    report += `Temperature value ${sensorValues.temperature} is out of range (${temperatureLow}-${temperatureHigh}).\n`;
                    allWithinLimits = false;
                } else {
                    report += `Temperature value ${sensorValues.temperature} is within range.\n`;
                }

                if (allWithinLimits) {
                    report += "All sensor values are within the specified limits.";
                }

                return report;
            }
    
            function pHLevelFetchData() {
                var currentTime = Date.now();
                var elapsedTime = currentTime - startTime;
    
                var analyzingTimeLimit = parseInt(document.getElementById('analyzingTime').value, 10);

                // Stop the function if 30 seconds have passed
                if (elapsedTime >= analyzingTimeLimit) {
                    stopUpdating();
                    return;
                }
    
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        var phLevel = data.phLevel; 
                        var TDSLevel = data.TDSLevel;
                        var turbidityLevel = data.turbidityLevel;
                        var temperatureLevel = data.temperatureLevel;
                        TDSGaugeUpdate(TDSLevel); // Assuming this is 
                        pHGaugeUpdate(phLevel); // Assuming this is a function you've defined elsewhere
                        turbidity_GaugeUpdate(turbidityLevel); // Assuming this is a function you've defined elsewhere
                        temperatureGaugeUpdate(temperatureLevel); // Assuming this is a function you've defined elsewhere
                        return[phLevel, TDSLevel, turbidityLevel, temperatureLevel];
                    }
                };
    
                var postData = JSON.stringify({});
                xhr.open('POST', 'controller/arduino-controller.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(postData);
            }
    
            // Initial fetch
            pHLevelFetchData();
            // Set an interval for continuous updates
            updateInterval = setInterval(pHLevelFetchData, 2000);
        }

        // Function to restart monitoring
        function restartMonitoring() {
            startMonitoring(); // Start monitoring again
            document.getElementById('restartBtn').style.display = "none";
            document.getElementById('saveBtn').style.display = 'none';                        

        }
        
        // Initial WiFi status check
        updateWifiStatus();
    
        // Set up the click event on the "Start Monitoring" button to start monitoring
        document.getElementById('startBtn').addEventListener('click', startMonitoring);
        document.getElementById('restartBtn').addEventListener('click', restartMonitoring);

    }
    
    // Call the function to set everything up
    pHsetupWiFiStatusCheckAndEnableMonitoring();
    


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

    am5.array.each(pHbandsData, function (data) {
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
            fontSize: "9px",
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
        innerRadius: -40
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
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
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
        fontSize: "1.3em"
    }));

    TDSaxisDataItem.set("value", 0);
    TDSbullet.get("sprite").on("rotation", function () {
        var value = TDSaxisDataItem.get("value");
        var text = Math.round(TDSaxisDataItem.get("value")).toString();
        var fill = am5.color(0x000000);
        TDSxAxis.axisRanges.each(function (TDSaxisRange) {
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
                TDSaxisDataItem.set("value", Number(interpolatedValue.toFixed(0)));
                document.getElementById('TDSValue').value = (interpolatedValue.toFixed(0));
                document.getElementById('TDSValue1').innerText = (interpolatedValue.toFixed(0)) + ' ppm';



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

    function TDSsetupWiFiStatusCheckAndEnableMonitoring() {
        // This function starts the pH monitoring process
        function startMonitoring() {
            var startTime = Date.now(); // Record the start time
            var updateInterval; // To hold the interval for updating the pH level
    
            function stopUpdating() {
                clearInterval(updateInterval); // Stop the interval
                console.log('Stopped updating pH level after 30 seconds.');
                document.getElementById('analyzingIcon').style.display = "none";
            }
    
            function TDSLevelFetchData() {
                var currentTime = Date.now();
                var elapsedTime = currentTime - startTime;
    
                // JavaScript retrieving the value
                var analyzingTimeLimit = parseInt(document.getElementById('analyzingTime').value, 10);

                // Use it in your conditions
                if (elapsedTime >= analyzingTimeLimit) {
                    stopUpdating();
                    return;
                }
                
                // var xhr = new XMLHttpRequest();
                // xhr.onreadystatechange = function() {
                //     if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                //         var data = JSON.parse(xhr.responseText);
                //         var TDSLevel = data.TDSLevel;
                //         TDSGaugeUpdate(TDSLevel); // Assuming this is a function you've defined elsewhere
                //     }
                // };
    
                // var postData = JSON.stringify({});
                // xhr.open('POST', 'controller/arduino-controller.php', true);
                // xhr.setRequestHeader('Content-Type', 'application/json');
                // xhr.send(postData);
            }
    
            // Initial fetch
            TDSLevelFetchData();
            // Set an interval for continuous updates
            updateInterval = setInterval(TDSLevelFetchData, 3000);
        }

          // Function to restart monitoring
          function restartMonitoring() {
            startMonitoring(); // Start monitoring again
        }
    
        // Set up the click event on the "Start Monitoring" button to start monitoring
        document.getElementById('startBtn').addEventListener('click', startMonitoring);
        document.getElementById('restartBtn').addEventListener('click', restartMonitoring);
    }
    
    // Call the function to set everything up
    TDSsetupWiFiStatusCheckAndEnableMonitoring();
    


    // Create axis ranges bands
    var TDSbandsData = [{
        color: "#f04922",
        lowScore: 0,
        highScore: 500
    }, {
        color: "#fdae19",
        lowScore: 500,
        highScore: 1000
    }, {
        color: "#f3eb0c",
        lowScore: 1000,
        highScore: 1500
    }, {
        color: "#b0d136",
        lowScore: 1500,
        highScore: 2000
    }];

    am5.array.each(TDSbandsData, function (data) {
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
            fontSize: "9px",
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
        innerRadius: -40
    });

    turbidity_axisRenderer.grid.template.setAll({
        stroke: turbidity_Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 0.8
    });

    var turbidity_xAxis = turbidity_chart.xAxes.push(am5xy.ValueAxis.new(turbidity_Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 1000,
        strictMinMax: true,
        renderer: turbidity_axisRenderer
    }));


    // Add clock hand
    var turbidity_axisDataItem = turbidity_xAxis.makeDataItem({});

    var turbidity_clockHand = am5radar.ClockHand.new(turbidity_Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
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
        fontSize: "1.3em"
    }));

    turbidity_axisDataItem.set("value", 0);
    turbidity_bullet.get("sprite").on("rotation", function () {
        var value = turbidity_axisDataItem.get("value");
        var text = Math.round(turbidity_axisDataItem.get("value")).toString();
        var fill = am5.color(0x000000);
        turbidity_xAxis.axisRanges.each(function (turbidity_axisRange) {
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
                turbidity_axisDataItem.set("value", Number(interpolatedValue.toFixed(0)));
                document.getElementById('turbidityValue').value = (interpolatedValue.toFixed(0));
                document.getElementById('turbidityValue1').innerText = (interpolatedValue.toFixed(0)) + ' NTU';



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

    function turbidtysetupWiFiStatusCheckAndEnableMonitoring() {

        // This function starts the pH monitoring process
        function startMonitoring() {

            var startTime = Date.now(); // Record the start time
            var updateInterval; // To hold the interval for updating the pH level
    
            function stopUpdating() {
                clearInterval(updateInterval); // Stop the interval
                console.log('Stopped updating pH level after 30 seconds.');
                document.getElementById('analyzingIcon').style.display = "none";
            }
    
            function temperatureLevelFetchData() {
                var currentTime = Date.now();
                var elapsedTime = currentTime - startTime;
    
                // JavaScript retrieving the value
                var analyzingTimeLimit = parseInt(document.getElementById('analyzingTime').value, 10);

                // Use it in your conditions
                if (elapsedTime >= analyzingTimeLimit) {
                    stopUpdating();
                    return;
                }
    
                // var xhr = new XMLHttpRequest();
                // xhr.onreadystatechange = function() {
                //     if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                //         var data = JSON.parse(xhr.responseText);
                //         var turbidityLevel = data.turbidityLevel;
                //         turbidity_GaugeUpdate(turbidityLevel); // Assuming this is a function you've defined elsewhere
                //     }
                // };
    
                // var postData = JSON.stringify({});
                // xhr.open('POST', 'controller/arduino-controller.php', true);
                // xhr.setRequestHeader('Content-Type', 'application/json');
                // xhr.send(postData);
            }
    
            // Initial fetch
            temperatureLevelFetchData();
            // Set an interval for continuous updates
            updateInterval = setInterval(temperatureLevelFetchData, 3000);
        }
    
        // Function to restart monitoring
        function restartMonitoring() {
            startMonitoring(); // Start monitoring again
        }
    
        // Set up the click event on the "Start Monitoring" button to start monitoring
        document.getElementById('startBtn').addEventListener('click', startMonitoring);
        document.getElementById('restartBtn').addEventListener('click', restartMonitoring);

    }
    
    // Call the function to set everything up
    turbidtysetupWiFiStatusCheckAndEnableMonitoring();
    


    // Create axis ranges bands
    var turbidity_bandsData = [{
        color: "#f04922",
        lowScore: 0,
        highScore: 200
    }, {
        color: "#fdae19",
        lowScore: 200,
        highScore: 400
    }, {
        color: "#f3eb0c",
        lowScore: 400,
        highScore: 600
    }, {
        color: "#b0d136",
        lowScore: 600,
        highScore: 800
    }, {
        color: "#6699ff",
        lowScore: 800,
        highScore: 1000
    }];

    am5.array.each(turbidity_bandsData, function (data) {
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
            fontSize: "9px",
            fill: turbidity_Gauge.interfaceColors.get("background")
        });
    });


    // Make stuff animate on load
    turbidity_chart.appear(1000, 100);

    // --------------------------------------------------------------------------------------------TEMPERATURE

    // TEMPERATURE LEVEL GAUGE
    var temperature_Gauge = am5.Root.new("temperature_Level");

    // Set themes
    // https://www.amcharts.com/docs/v5/concepts/themes/
    temperature_Gauge.setThemes([
        am5themes_Animated.new(temperature_Gauge)
    ]);


    // Create temperature_chart
    // https://www.amcharts.com/docs/v5/charts/radar-chart/
    var temperature_chart = temperature_Gauge.container.children.push(am5radar.RadarChart.new(temperature_Gauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));


    // Create axis and its renderer
    // https://www.amcharts.com/docs/v5/charts/radar-temperature_chart/gauge-charts/#Axes
    var temperature_axisRenderer = am5radar.AxisRendererCircular.new(temperature_Gauge, {
        innerRadius: -40
    });

    temperature_axisRenderer.grid.template.setAll({
        stroke: temperature_Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var temperature_xAxis = temperature_chart.xAxes.push(am5xy.ValueAxis.new(temperature_Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 100,
        strictMinMax: true,
        renderer: temperature_axisRenderer
    }));


    // Add clock hand
    var temperature_axisDataItem = temperature_xAxis.makeDataItem({});

    var temperature_clockHand = am5radar.ClockHand.new(temperature_Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    })

    var temperature_bullet = temperature_axisDataItem.set("bullet", am5xy.AxisBullet.new(temperature_Gauge, {
        sprite: temperature_clockHand
    }));

    temperature_xAxis.createAxisRange(temperature_axisDataItem);

    var temperature_label = temperature_chart.radarContainer.children.push(am5.Label.new(temperature_Gauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    temperature_axisDataItem.set("value", 0);
    temperature_bullet.get("sprite").on("rotation", function () {
        var value = temperature_axisDataItem.get("value");
        var text = Math.round(temperature_axisDataItem.get("value")).toString();
        var fill = am5.color(0x000000);
        temperature_xAxis.axisRanges.each(function (temperature_axisRange) {
            if (value >= temperature_axisRange.get("value") && value <= temperature_axisRange.get("endValue")) {
                fill = temperature_axisRange.get("axisFill").get("fill");
            }
        })
        temperature_label.set("text", value.toFixed(2).toString());


        temperature_clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        })
        temperature_clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        })
    });

    // Define variables for animation control
    var temperature_currentPhLevel = 0; // Current temperature_ level displayed
    var temperature_targetPhLevel = 0; // Target temperature_ level for interpolation
    var temperature_animationStartTime = performance.now(); // Timestamp to track animation start time
    var temperature_animationDuration = 800; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with smooth animation
    // Function to update the gauge with smooth animation
    function temperatureGaugeUpdate(temperature_Level) {
        // Parse the new temperature_ level as a floating-point number
        var parsedPhLevel = parseFloat(temperature_Level);

        if (!isNaN(parsedPhLevel)) {
            temperature_targetPhLevel = parsedPhLevel; // Set the new target temperature_ level

            // Update the gauge smoothly using requestAnimationFrame
            function animate() {
                var now = performance.now();
                var progress = (now - temperature_animationStartTime) / temperature_animationDuration;

                // Perform linear interpolation between current and target values
                var interpolatedValue = temperature_currentPhLevel + (temperature_targetPhLevel - temperature_currentPhLevel) * progress;

                // Update the gauge with the interpolated value
                // Use Number.toFixed(2) to format with two decimal places
                temperature_axisDataItem.set("value", Number(interpolatedValue.toFixed(2)));
                document.getElementById('temperatureValue').value = (interpolatedValue.toFixed(2));
                document.getElementById('temperatureValue1').innerText = (interpolatedValue.toFixed(2)) + '°C';

                // Continue animation until duration is reached
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    // Animation completed, update current value
                    temperature_currentPhLevel = temperature_targetPhLevel;
                }
            }

            // Start the animation
            temperature_animationStartTime = performance.now();
            animate();
        } else {
            console.error('Invalid temperature_ level received:', temperature_Level);
        }
    }


    function temperaturesetupWiFiStatusCheckAndEnableMonitoring() {
        // This function starts the pH monitoring process
        function startMonitoring() {

            var startTime = Date.now(); // Record the start time
            var updateInterval; // To hold the interval for updating the pH level
    
            function stopUpdating() {
                clearInterval(updateInterval); // Stop the interval
                console.log('Stopped updating pH level after 30 seconds.');
                document.getElementById('analyzingIcon').style.display = "none";
            }
    
            function temperatureLevelFetchData() {
                var currentTime = Date.now();
                var elapsedTime = currentTime - startTime;
    
                // JavaScript retrieving the value
                var analyzingTimeLimit = parseInt(document.getElementById('analyzingTime').value, 10);

                // Use it in your conditions
                if (elapsedTime >= analyzingTimeLimit) {
                    stopUpdating();
                    return;
                }
    
                // var xhr = new XMLHttpRequest();
                // xhr.onreadystatechange = function() {
                //     if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                //         var data = JSON.parse(xhr.responseText);
                //         var temperatureLevel = data.temperatureLevel;
                //         temperatureGaugeUpdate(temperatureLevel); // Assuming this is a function you've defined elsewhere
                //     }
                // };
    
                // var postData = JSON.stringify({});
                // xhr.open('POST', 'controller/arduino-controller.php', true);
                // xhr.setRequestHeader('Content-Type', 'application/json');
                // xhr.send(postData);
            }
    
            // Initial fetch
            temperatureLevelFetchData();
            // Set an interval for continuous updates
            updateInterval = setInterval(temperatureLevelFetchData, 3000);
        }
    
        // Initial WiFi status check
        function restartMonitoring() {
            startMonitoring(); // Start monitoring again
        }
    
    
        // Set up the click event on the "Start Monitoring" button to start monitoring
        document.getElementById('startBtn').addEventListener('click', startMonitoring);
        document.getElementById('restartBtn').addEventListener('click', restartMonitoring);
    }
    
    // Call the function to set everything up
    temperaturesetupWiFiStatusCheckAndEnableMonitoring();

    // Create axis ranges bands
    var temperature_bandsData = [{
        color: "#f04922",
        lowScore: 0,
        highScore: 20
    }, {
        color: "#fdae19",
        lowScore: 20,
        highScore: 40
    }, {
        color: "#f3eb0c",
        lowScore: 40,
        highScore: 60
    }, {
        color: "#b0d136",
        lowScore: 60,
        highScore: 80
    }, {
        color: "#6699ff",
        lowScore: 80,
        highScore: 100
    }];

    am5.array.each(temperature_bandsData, function (data) {
        var temperature_axisRange = temperature_xAxis.createAxisRange(temperature_xAxis.makeDataItem({}));

        temperature_axisRange.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });

        temperature_axisRange.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });

        temperature_axisRange.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: temperature_Gauge.interfaceColors.get("background")
        });
    });


    // Make stuff animate on load
    temperature_chart.appear(1000, 100);