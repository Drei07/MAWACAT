<!-- #include <WiFi.h>
#include <ArduinoJson.h>
#include <WebServer.h>
#include <Arduino.h>
#include <OneWire.h>
#include <DallasTemperature.h>

const char* ssid = "PLDTHOMEFIBRV75Zz";
const char* password = "Andreishania07012000@";
WebServer server(80);

//TDS
const int TDS_SENSOR_PIN = 34; // TDS sensor connected to GPIO pin 25 (D25)
const float TDS_CALIBRATION_FACTOR = 0.5; // Calibration factor (adjust based on your sensor)
const float TDS_CALIBRATION_OFFSET = 0.0; // Calibration offset (adjust based on your sensor)

//pH
const int pH_SENSOR_PIN = 35; // Assuming pH sensor connected to pin 35
float pH_Value;

//TEMPERATURE
#define ONE_WIRE_BUS 32 // Pin where the DS18B20 is connected
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

void setup() {
  Serial.begin(9600);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nWiFi connected!");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
  server.on("/", HTTP_GET, handleRoot);
  server.on("/arduino_connection", HTTP_GET, handleCheckConnection); // Handle check connection request
  server.begin();
}


void loop() {
    server.handleClient();
}


void handleRoot() {
  String htmlContent = "<!DOCTYPE html><html lang='en'><head>";
  htmlContent += "<meta charset='UTF-8'>";
  htmlContent += "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
  htmlContent += "<title>Arduino Connection Status</title>";
  htmlContent += "</head><body>";
  htmlContent += "<h1>Arduino Connection Status</h1>";
  htmlContent += "<p id='wifi_status'>Wifi Status: Unknown</p>";
  htmlContent += "<p id='phLevel_status'>pH Level Status: Unknown</p>";
  htmlContent += "<p id='TDSLevel_status'>TDS Level Status: Unknown</p>";
  htmlContent += "<p id='turbidityLevel_status'>Turbidity Level Status: Unknown</p>";
  htmlContent += "<script>";
  htmlContent += "function checkStatus() {";
  htmlContent += "var xhr = new XMLHttpRequest();";
  htmlContent += "xhr.onreadystatechange = function() {";
  htmlContent += "if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {";
  htmlContent += "var data = JSON.parse(xhr.responseText);";
  htmlContent += "document.getElementById('wifi_status').innerText = 'Wifi Status: ' + data.wifi_status;";
  htmlContent += "document.getElementById('phLevel_status').innerText = 'pH Level Status: ' + data.phLevel;";
  htmlContent += "document.getElementById('TDSLevel_status').innerText = 'TDS Level Status: ' + data.TDSLevel;";
  htmlContent += "document.getElementById('turbidityLevel_status').innerText = 'Turbidity Level Status: ' + data.turbidityLevel;";
  htmlContent += "}";
  htmlContent += "};";
  htmlContent += "xhr.open('GET', 'arduino_connection', true);"; // Use the appropriate endpoint
  htmlContent += "xhr.send();";
  htmlContent += "}";
  htmlContent += "setInterval(checkStatus, 1000);"; // Check status every second
  htmlContent += "</script></body></html>";

  server.send(200, "text/html", htmlContent);
}

void handleCheckConnection() {

  int TDSsensorValue = analogRead(TDS_SENSOR_PIN);
  float TDS_Value = TDSsensorValue * TDS_CALIBRATION_FACTOR + TDS_CALIBRATION_OFFSET;
  Serial.print("TDS Value: ");
  Serial.print(TDS_Value);
  Serial.println(" ppm");

  int pHsensorValue = analogRead(pH_SENSOR_PIN);
  float scalingFactor = (2.57 - 2.55) / (9.35 - 9.32);
  pH_Value = (pHsensorValue * (3.3 / 1023.0) - 9.32) * scalingFactor + 2.55;

  Serial.print("pH Value: ");
  Serial.print(pH_Value, 2);
  Serial.println(" pH");

  sensors.requestTemperatures(); // Send the command to get temperatures
  float temperature_Value = sensors.getTempCByIndex(0); // Read temperature in Celsius

  if (temperature_Value != DEVICE_DISCONNECTED_C) {
    Serial.print("Water Temperature: ");
    Serial.print(temperature_Value);
    Serial.println(" °C");
  } else {
    Serial.println("Error: Unable to read temperature!");
  }
  Serial.println("");
  
  delay(1000); // Delay for 1 second before the next reading

  StaticJsonDocument<256> jsonDoc;
  jsonDoc["wifi_status"] = (WiFi.status() == WL_CONNECTED) ? "Connected" : "Not Connected";
  jsonDoc["TDSLevel"] = TDS_Value;
  jsonDoc["phLevel"] = String(pH_Value, 2);
  jsonDoc["temperatureLevel"] = temperature_Value;


  // Serialize the JSON document to a string
  String response;
  serializeJson(jsonDoc, response);

  // Send the JSON response
  server.send(200, "application/json", response);
} -->

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
  <p id="temperatureLevel_status">Temperature Level Status: Unknown</p>

  <script>
    var ws = new WebSocket('ws://192.168.1.115:81/');

    ws.onopen = function() {
      console.log('WebSocket connection established');
    };

    ws.onmessage = function(event) {
      var data = JSON.parse(event.data);
      document.getElementById('wifi_status').innerText = 'Wifi Status: ' + data.wifi_status;
      document.getElementById('phLevel_status').innerText = 'pH Level Status: ' + data.phLevel;
      document.getElementById('TDSLevel_status').innerText = 'TDS Level Status: ' + data.TDSLevel;
      document.getElementById('temperatureLevel_status').innerText = 'Temperature Level Status: ' + data.temperatureLevel;
    };

    ws.onclose = function() {
      console.log('WebSocket connection closed');
    };

    ws.onerror = function(error) {
      console.error('WebSocket error: ', error);
    };
  </script>
</body>
</html>
