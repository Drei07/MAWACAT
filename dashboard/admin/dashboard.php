<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_dashboard->getHeaderDashboard() ?>
    <script src="http://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.7/all/gauge.min.js"></script>

    <title>Water Analyzer Dashboard</title>
</head>

<body>

    <div class="topnav">
        <h1 id="wifi_status">Device Status: Connecting........</h1>
    </div>

    <div class="gauge_dashboard">
        <div class="gauge phLevel">
            <div class="card level_card">
                <p class="level" id="pH-display" >00.00</p>
            </div>
            <div class="card gauge_card">
                <p class="card-title">PH Level Gauge</p>
                <canvas id="gauge-pH"></canvas>
            </div>
            <div class="card graphs_card">
                c
            </div>
        </div>

        <div class="gauge tdsLevel">
            <div class="card level_card">
            <p class="level" id="TDS-display">00</p>
            </div>
            <div class="card gauge_card">
                <p class="card-title">Turbidity Level Gauge</p>
                <canvas id="gauge-Turbidity"></canvas>
            </div>
            <div class="card graphs_card">
                c
            </div>
        </div>

        <div class="gauge turbidtyLevel">
            <div class="card level_card">
                <p class="level" id="turbidity-display">00</p>
            </div>
            <div class="card gauge_card">
                <p class="card-title">TDS Level Gauge</p>
                <canvas id="gauge-TDS"></canvas>
            </div>
            <div class="card graphs_card">
                c
            </div>
        </div>
    </div>

    <button id="startBtn" style="display:none;" onclick="startMonitoring()">Start Monitoring</button>

    <script>
        var startTime = Date.now(); // Record the start time when the script begins
        var wifiInterval = setInterval(updateWifiStatus, 3000); // Update WiFi status every 3 seconds
        var interval; // Variable to hold the interval for checking status

        function updateWifiStatus() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById('wifi_status').innerText = 'Device Status: ' + data.wifi_status;
                    if (data.wifi_status === 'Connected') {
                        document.getElementById('startBtn').style.display = 'inline-block';
                    } else {
                        document.getElementById('startBtn').style.display = 'none';
                    }
                }
            };

            xhr.open('GET', 'controller/arduino-controller', true);
            xhr.send();
        }

        function startMonitoring() {
            startTime = Date.now(); // Reset start time
            clearInterval(interval); // Clear any existing interval
            interval = setInterval(checkStatus, 1000); // Start checking status every 1 second
        }

        function checkStatus() {
            var currentTime = Date.now(); // Get the current time
            var elapsedTime = currentTime - startTime; // Calculate elapsed time

            // Check if 10 seconds have passed
            if (elapsedTime >= 10000) { // 10000 milliseconds = 10 seconds
                clearInterval(interval); // Stop updating pH, turbidity, and TDS values
                console.log('Stopped updating sensor values after 10 seconds.');
                return; // Exit the function
            }

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);

                    // Update pH, turbidity, and TDS values after a delay

                        gaugePH.value = data.phLevel;
                        gaugePH.update();

                        gaugeTurbidity.value = data.turbidityLevel;
                        gaugeTurbidity.update();

                        gaugeTDS.value = data.TDSLevel;
                        gaugeTDS.update();

                        // Update display elements
                        document.getElementById('pH-display').innerText = data.phLevel;
                        document.getElementById('turbidity-display').innerText = data.turbidityLevel;
                        document.getElementById('TDS-display').innerText = data.TDSLevel;
                }
            };

            // Prepare JSON data to send in the POST request
            var postData = JSON.stringify({});

            xhr.open('POST', 'controller/arduino-controller.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(postData);
        }

        // Function to create and initialize a gauge
        function createGauge(elementId, units, minValue, maxValue, colorRange, initialValue, majorTicks) {
            var gauge = new RadialGauge({
                renderTo: elementId,
                width: 180,
                height: 180,
                units: units,
                minValue: minValue,
                maxValue: maxValue,
                valueInt: initialValue,
                valueBox: false,
                majorTicks: majorTicks,
                minorTicks: 4,
                highlights: colorRange,
                colorPlate: "#fff",
                borderShadowWidth: 0,
                borders: false,
                needleType: "arrow",
                colorNeedle: "#000",
                colorNeedleEnd: "#000",
                needleWidth: 3,
                needleCircleSize: 7,
                colorNeedleCircleOuter: "#000",
                needleCircleOuter: true,
                needleCircleInner: false,
                animationDuration: 1000,
                animationRule: "linear"
            }).draw();
            return gauge;
        }

        // Create pH Level Gauge
        var gaugePH = createGauge('gauge-pH', 'pH Level', 0, 20, [{
                "from": 0,
                "to": 4,
                "color": "#e6e6e6"
            }, // Acidic range
            {
                "from": 4,
                "to": 8,
                "color": "#d9d9d9"
            }, // Neutral range
            {
                "from": 8,
                "to": 12,
                "color": "#cccccc"
            }, // Alkaline range
            {
                "from": 12,
                "to": 16,
                "color": "#b3b3b3"
            },
            {
                "from": 16,
                "to": 20,
                "color": "#999999"
            }  // Alkaline range
        ], 0, ["0", "2", "4", "6", "8", "10", "12", "14", "16", "18", "20"]);

        // Create Turbidity Level Gauge
        var gaugeTurbidity = createGauge('gauge-Turbidity', 'Turbidity Level', 0, 100, [{
                "from": 0,
                "to": 25,
                "color": "#e6e6e6"
            }, // Low turbidity
            {
                "from": 25,
                "to": 50,
                "color": "#cccccc"
            }, // High turbidity
            {
                "from": 50,
                "to": 75,
                "color": "#b3b3b3"
            }, // High turbidity
            {
                "from": 75,
                "to": 100,
                "color": "#999999"
            } // High turbidity
        ], 0, ["0", "25", "50", "75", "100"]);

        // Create TDS Level Gauge
        var gaugeTDS = createGauge('gauge-TDS', 'TDS Level', 0, 2000, [{
                "from": 0,
                "to": 500,
                "color": "#e6e6e6"
            }, // Low TDS
            {
                "from": 500,
                "to": 1000,
                "color": "#cccccc"
            }, // Medium TDS
            {
                "from": 1000,
                "to": 1500,
                "color": "#b3b3b3"
            },
            {
                "from": 1500,
                "to": 2000,
                "color": "#999999"
            } // High TDS
        ], 0, ["0", "500", "1000", "1500", "2000"]);
    </script>
</body>
</html>