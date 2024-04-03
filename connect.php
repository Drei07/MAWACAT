<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arduino Connection Status</title>
</head>
<body>
  <h1>Arduino Connection Status</h1>
  <p id="wifi_status">Wifi Status: Unknown</p>
  <p id="phLevel_status">pH Level Status: Unknown</p>
  <p id="TDSLevel_status">TDS Level Status: Unknown</p>
  <p id="turbidityLevel_status">Turbidity Level Status: Unknown</p>

  <script>
    function checkStatus() {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          document.getElementById('wifi_status').innerText = 'Wifi Status: ' + data.wifi_status;
          document.getElementById('phLevel_status').innerText = 'pH Level Status: ' + data.phLevel;
          document.getElementById('TDSLevel_status').innerText = 'TDS Level Status: ' + data.TDSLevel;
          document.getElementById('turbidityLevel_status').innerText = 'Turbidity Level Status: ' + data.turbidityLevel;
        }
      };
      
      // Prepare JSON data to send in the POST request
      var postData = JSON.stringify({});

      xhr.open('POST', 'arduino_connection.php', true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(postData);
    }

    setInterval(checkStatus, 1000); // Check status every 1 second
  </script>
</body>
</html>


<!DOCTYPE html>
<html>

<head>
    <title>ESP IOT DASHBOARD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="http://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.7/all/gauge.min.js"></script>
</head>
<style>
    html {
        font-family: Arial, Helvetica, sans-serif;
        display: inline-block;
        text-align: center;
    }

    h1 {
        font-size: 1.8rem;
        color: white;
    }

    p {
        font-size: 1.4rem;
    }

    .topnav {
        overflow: hidden;
        background-color: #0A1128;
    }

    body {
        margin: 0;
    }

    .content {
        padding: 5%;
    }

    .card-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-gap: 2rem;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .card {
        background-color: white;
        box-shadow: 2px 2px 12px 1px rgba(140, 140, 140, .5);
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: #034078
    }
</style>

<body>
    

    <div class="content">
        <div class="card-grid">
            <div class="card">
                <p class="card-title">PH Level</p>
                <canvas id="gauge-pH"></canvas>
                <div id="pH-display">0.00</div> <!-- Initial value displayed -->

            </div>
            <div class="card">
                <p class="card-title">Turbidity Level</p>
                <canvas id="gauge-Turbidity"></canvas>
                <div id="turbidity-display">0.00</div> <!-- Initial value displayed -->

            </div>
            <div class="card">
                <p class="card-title">TDS Level</p>
                <canvas id="gauge-TDS"></canvas>
                <div id="TDS-display">0.00</div> <!-- Initial value displayed -->

            </div>
        </div>
    </div>
    <script>
        // Function to update gauges with random values
        function updateGaugesRandom() {
            var randomPH = Math.random() * 14;
            var roundedPH = randomPH.toFixed(2); // Round to 1 decimal place
            var randomTurbidity = Math.random() * 100;
            var roundedTurbidity = randomTurbidity.toFixed(2); // Round to 1 decimal place
            var randomTDS = Math.random() * 2000;
            var roundedTDS = randomTDS.toFixed(2); // Round to 1 decimal place

            gaugePH.value = roundedPH;
            gaugePH.update();
            gaugeTurbidity.value = roundedTurbidity;
            gaugeTurbidity.update();
            gaugeTDS.value = roundedTDS;
            gaugeTDS.update();
            document.getElementById('pH-display').innerText = roundedPH; // Update displayed value
            document.getElementById('turbidity-display').innerText = roundedTurbidity; // Update displayed value
            document.getElementById('TDS-display').innerText = roundedTDS; // Update displayed value


        }

        function checkStatus() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);

                    gaugePH.value =  data.phLevel;
                    gaugePH.update();

                    gaugeTurbidity.value = data.turbidityLevel;
                    gaugeTurbidity.update();

                    gaugeTDS.value = data.TDSLevel;
                    gaugeTDS.update();

                    document.getElementById('wifi_status').innerText = 'Wifi Status: ' + data.wifi_status;
                    document.getElementById('phLevel_status').innerText = 'pH Level Status: ' + data.phLevel;
                    document.getElementById('TDSLevel_status').innerText = 'TDS Level Status: ' + data.TDSLevel;
                    document.getElementById('turbidityLevel_status').innerText = 'Turbidity Level Status: ' + data.turbidityLevel;
                }
            };

            // Prepare JSON data to send in the POST request
            var postData = JSON.stringify({});

            xhr.open('POST', 'arduino_connection.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(postData);
        }

        setInterval(checkStatus, 1000); // Check status every 1 second

        // Simulate continuous updates (every 1 second for demonstration)
        setInterval(updateGaugesRandom, 1000);
        // Function to create and initialize a gauge
        function createGauge(elementId, units, minValue, maxValue, colorRange, initialValue, majorTicks) {
            var gauge = new RadialGauge({
                renderTo: elementId,
                width: 300,
                height: 300,
                units: units,
                minValue: minValue,
                maxValue: maxValue,
                colorValueBoxRect: "#049faa",
                colorValueBoxRectEnd: "#049faa",
                colorValueBoxBackground: "#f1fbfc",
                valueInt: initialValue,
                valueBox: false,
                majorTicks: majorTicks,
                minorTicks: 6,
                highlights: colorRange,
                colorPlate: "#fff",
                borderShadowWidth: 0,
                borders: false,
                needleType: "arrow",
                colorNeedle: "#007F80",
                colorNeedleEnd: "#007F80",
                needleWidth: 4,
                needleCircleSize: 10,
                colorNeedleCircleOuter: "#007F80",
                needleCircleOuter: true,
                needleCircleInner: false,
                animationDuration: 1500,
                animationRule: "linear"
            }).draw();
            return gauge;
        }

        // Create pH Level Gauge
        var gaugePH = createGauge('gauge-pH', 'pH Level', 0, 14, [{
                "from": 0,
                "to": 4,
                "color": "#ff5c33"
            }, // Acidic range
            {
                "from": 4,
                "to": 10,
                "color": "#66ff66"
            }, // Neutral range
            {
                "from": 10,
                "to": 14,
                "color": "#8585e0"
            } // Alkaline range
        ], 0, ["0", "2", "4", "6", "8", "10", "12", "14"]);

        // Create Turbidity Level Gauge
        var gaugeTurbidity = createGauge('gauge-Turbidity', 'Turbidity Level', 0, 100, [{
                "from": 0,
                "to": 50,
                "color": "#ff5c33"
            }, // Low turbidity
            {
                "from": 50,
                "to": 100,
                "color": "#66ff66"
            } // High turbidity
        ], 0, ["0", "25", "50", "75", "100"]);

        // Create TDS Level Gauge
        var gaugeTDS = createGauge('gauge-TDS', 'TDS Level', 0, 2000, [{
                "from": 0,
                "to": 500,
                "color": "#ff5c33"
            }, // Low TDS
            {
                "from": 500,
                "to": 1500,
                "color": "#66ff66"
            }, // Medium TDS
            {
                "from": 1500,
                "to": 2000,
                "color": "#8585e0"
            } // High TDS
        ], 0, ["0", "500", "1000", "1500", "2000"]);
    </script>
</body>

</html>