#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <HardwareSerial.h>
#include <Wire.h>
#include <Adafruit_MLX90640.h>

HardwareSerial mySerial(1);  // Use UART1 for the PMS5003ST
Adafruit_MLX90640 mlx;

// Wi-Fi Credentials
const char* ssid = "PLDTHOMEFIBRV75Zz";
const char* password = "Andreishania07012000@";

const char* captureStatusURL = "https://adutect.website/dashboard/admin/controller/fetch_data.php";  // Your server URL for fetching thresholds

const int fanPin = 15;
const int infaredSensorPin = 2;
const int IRSensor1Pin = 33;
const int IRSensor2Pin = 32;
const float IRSensoRdetectionThreshold = 0.20;  // Voltage threshold for object detection
const int smokeSensorPin1 = 35;
const int smokeSensorPin2 = 34;
const int vibrateSensorPin = 26;
const int buzzerPin = 27;
unsigned long buzzerStartTime = 0;     // Time when the buzzer was activated
unsigned long buzzerDuration = 20000;  // Duration to keep the buzzer on (20 seconds)
bool buzzerState = false;              // To keep track of buzzer's state (on/off)
const int RxAirSensorPin = 16;         // RX pin (connected to TX of PMS5003ST)
const int TxAirSensorPin = 17;         // TX pin (connected to RX of PMS5003ST)
const int AirSensorSetPin = 5;
unsigned long pmsStartTime = 0;  // Variable to store the start time of PMS activation
bool pmsRunning = false;         // Flag to track if PMS is currently active
String RoomId = "R102";
bool startReadingPMS =false;


// Data structure to hold the PM values
struct PMData {
  uint16_t pm1_0;     // PM1.0 concentration in µg/m³
  uint16_t pm2_5;     // PM2.5 concentration in µg/m³
  uint16_t pm10;      // PM10 concentration in µg/m³
  uint16_t temp;      // Temperature in 0.1°C
  uint16_t humidity;  // Humidity in 0.1%

  // Particle count values
  uint16_t count_0_3;  // Particle count (0.3µm)
  uint16_t count_0_5;  // Particle count (0.5µm)
  uint16_t count_1_0;  // Particle count (1.0µm)
  uint16_t count_2_5;  // Particle count (2.5µm)
  uint16_t count_5_0;  // Particle count (5.0µm)
  uint16_t count_10;   // Particle count (10µm)
};

// PMS5003ST data packet size
#define DATA_SIZE 32
uint8_t data[DATA_SIZE];  // Buffer to store the incoming data

PMData pmData;  // Structure to store extracted PM data

void setup() {
  Serial.begin(115200);  // Start serial monitor
  WiFi.begin(ssid, password);
  Wire.begin(21, 22);
  Wire.setClock(100000);                                             // Connect to Wi-Fi
  mySerial.begin(9600, SERIAL_8N1, RxAirSensorPin, TxAirSensorPin);  // 9600 baud rate for PMS5003ST

  bool sensorInitialized = false;
  while (!sensorInitialized) {
    sensorInitialized = mlx.begin(0x33, &Wire);
    if (!sensorInitialized) {
      Serial.println("MLX90640 initialization failed, retrying...");
      delay(500);  // Wait before retrying
    }
  }

  mlx.setMode(MLX90640_INTERLEAVED);
  mlx.setResolution(MLX90640_ADC_19BIT);
  mlx.setRefreshRate(MLX90640_8_HZ);

  Serial.println("MLX90640 ready!");

  // Wait for Wi-Fi connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(200);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");

  pinMode(AirSensorSetPin, OUTPUT);
  pinMode(fanPin, OUTPUT);
  pinMode(infaredSensorPin, OUTPUT);
  pinMode(IRSensor1Pin, INPUT);
  pinMode(IRSensor2Pin, INPUT);
  pinMode(smokeSensorPin1, INPUT);
  pinMode(smokeSensorPin2, INPUT);
  pinMode(vibrateSensorPin, INPUT);
  pinMode(buzzerPin, OUTPUT);

  digitalWrite(fanPin, HIGH);          // Fan off initially
  digitalWrite(AirSensorSetPin, LOW);  // Set PMS5003 to sleep mode on startup

  digitalWrite(infaredSensorPin, LOW);
  delay(1000);
  digitalWrite(infaredSensorPin, HIGH);
  delay(1000);
  digitalWrite(infaredSensorPin, LOW);
}

void loop() {

  if (smokeDetect() >= 2000) {  // Check if smoke detected
    fanOpen();
    Serial.println("Smoke Detected");
  } else {
    Serial.println("No Smoke Detected");
    Serial.println(smokeDetect());
    Serial.println(smokeVolumeAmount());

    if (smokeVolumeAmount() >= 90) {
      fanClose();
     if(!startReadingPMS){
      pmsActive();
      startReadingPMS = true;
      Serial.println("PMS Active Started for 30 seconds");
     }
    }
  }

  if(startReadingPMS){
    readPMSData();
    Serial.println("Starting Reading PMS");
  }

  // Check if any sensor is triggered
  if (IRsensorDetect1() == HIGH || IRsensorDetect2() == HIGH || vibrateSensor() == HIGH && smokeDetect() <= 900) {
    if (!buzzerState) {            // If the buzzer is not already on
      buzzerStartTime = millis();  // Store the current time when the buzzer starts
      buzzerState = true;          // Set buzzer state to ON
      buzzerAlarmOn();             // Turn on the buzzer
      String AlertMessage = "Tampering Alert";
      String CaptureStatus = "CAPTURE";
      sendCaptureData(AlertMessage, CaptureStatus, RoomId);
      Serial.println("Warning: Tampering");  // Output tampering message
    }

  } else {
    // If 20 seconds have passed, turn off the buzzer
    if (buzzerState && (millis() - buzzerStartTime >= buzzerDuration)) {
      buzzerState = false;  // Set buzzer state to OFF
      buzzerAlarmOff();     // Turn off the buzzer
    }
  }

  // Check infrared camera for high temperature condition
  if (checkHighTemperature() && smokeDetect() >= 2500) {
    if (!buzzerState) {            // If the buzzer is not already on
      buzzerStartTime = millis();  // Store the current time when the buzzer starts
      buzzerState = true;          // Set buzzer state to ON
      buzzerAlarmOn();             // Turn on the buzzer
      String AlertMessage = "Fire Alert";
      String CaptureStatus = "CAPTURE";
      sendCaptureData(AlertMessage, CaptureStatus, RoomId);
      Serial.println("Warning: Fire Alert!");  // Output tampering message
    }
  } else {
    // If 20 seconds have passed, turn off the buzzer
    if (buzzerState && (millis() - buzzerStartTime >= buzzerDuration)) {
      buzzerState = false;  // Set buzzer state to OFF
      buzzerAlarmOff();     // Turn off the buzzer
    }
  }
  delay(500);  // Short delay for stability
}

// Function to check if any pixel exceeds 60°C in the infrared camera frame
bool checkHighTemperature() {
  float frame[32 * 24];  // MLX90640 has 32x24 resolution

  // Attempt to read a frame of temperature data
  if (mlx.getFrame(frame) != 0) {
    Serial.println("Failed to read frame");
    delay(100);
    return false;  // Return false if frame reading failed
  }

  // Check for any temperature above 60°C in the frame
  for (int i = 0; i < 32 * 24; i++) {
    if (frame[i] > 60.0) {  // If temperature is greater than 60°C
      Serial.print("Warning: High temperature detected at pixel ");
      Serial.print(i);
      Serial.print(": ");
      Serial.println(frame[i]);
      return true;  // Return true if a high temperature is detected
    }
  }

  return false;  // Return false if no high temperature is detected
}

void pmsActive() {
  digitalWrite(AirSensorSetPin, HIGH);  // Activate PMS5003ST
}

void pmsSleep() {
  digitalWrite(AirSensorSetPin, LOW);  // Activate PMS5003ST
}

void readPMSData() {

      if (mySerial.available()) {
        if (mySerial.readBytes(data, DATA_SIZE) == DATA_SIZE) {
          if (data[0] == 0x42 && data[1] == 0x4D) {
            pmData.pm1_0 = (data[2] << 8) | data[3];
            pmData.pm2_5 = (data[4] << 8) | data[5];
            pmData.pm10 = (data[6] << 8) | data[7];

            pmData.count_0_3 = (data[10] << 8) | data[11];
            pmData.count_0_5 = (data[12] << 8) | data[13];
            pmData.count_1_0 = (data[14] << 8) | data[15];
            pmData.count_2_5 = (data[16] << 8) | data[17];
            pmData.count_5_0 = (data[18] << 8) | data[19];
            pmData.count_10 = (data[20] << 8) | data[21];

            pmData.temp = (data[28] << 8) | data[29];
            pmData.humidity = (data[30] << 8) | data[31];

            float temperatureC = pmData.temp / 10.0;
            float humidityPercent = pmData.humidity / 10.0;

            Serial.print("PM1.0: ");
            Serial.print(pmData.pm1_0);
            Serial.print(" µg/m³ | PM2.5: ");
            Serial.print(pmData.pm2_5);
            Serial.print(" µg/m³ | PM10: ");
            Serial.print(pmData.pm10);
            Serial.print(" µg/m³ | Temperature: ");
            Serial.print(temperatureC);
            Serial.print(" °C | Humidity: ");
            Serial.print(humidityPercent);
            Serial.print(" % | ");
            Serial.print("Particle Count (0.3µm): ");
            Serial.print(pmData.count_0_3);
            Serial.print(" | Particle Count (0.5µm): ");
            Serial.print(pmData.count_0_5);
            Serial.print(" | Particle Count (1.0µm): ");
            Serial.print(pmData.count_1_0);
            Serial.print(" | Particle Count (2.5µm): ");
            Serial.print(pmData.count_2_5);
            Serial.print(" | Particle Count (5.0µm): ");
            Serial.print(pmData.count_5_0);
            Serial.print(" | Particle Count (10µm): ");
            Serial.println(pmData.count_10);
            
            // Check for smoke from burning objects
            if ( pmData.count_2_5 >= 28041 && pmData.count_5_0 >= 9273 && pmData.count_10 >= 15502) {
              Serial.println("Detected: Smoke from burning objects");
              String AlertMessage = "Detected: Smoke from burning objects";
              String CaptureStatus = "CAPTURE";
              startReadingPMS = false;
              if(!startReadingPMS){
                pmsSleep();  // Stop PMS active operation
                Serial.println("PMS Active Stopped");
              }
              return sendCaptureData(AlertMessage, CaptureStatus, RoomId);
            }
            // Check for vape aerosols
            if (pmData.count_2_5 == 0 && pmData.count_5_0 == 0 && pmData.count_10 == 0) {
              Serial.println("Detected: Vape aerosol");
              String AlertMessage = "Detected: Vape Smoke";
              String CaptureStatus = "CAPTURE";
              startReadingPMS = false;
              if(!startReadingPMS){
                pmsSleep();  // Stop PMS active operation
                Serial.println("PMS Active Stopped");
              }
              return sendCaptureData(AlertMessage, CaptureStatus, RoomId);
            }
          }
        }
      }
}


int smokeDetect() {
  int smokeVolume = analogRead(smokeSensorPin1);
  // Convert the analog value to voltage (0-3.3V for ESP32 ADC)
  return smokeVolume;
}


int smokeVolumeAmount() {
  int smokeVolume = analogRead(smokeSensorPin2);
  // Convert the analog value to voltage (0-3.3V for ESP32 ADC)
  return smokeVolume;
}

int vibrateSensor() {
  return digitalRead(vibrateSensorPin);  // Return smoke sensor state
}

void fanOpen() {
  digitalWrite(fanPin, LOW);  // Turn fan on
}

void fanClose() {
  digitalWrite(fanPin, HIGH);  // Turn fan off
}

int IRsensorDetect1() {
  int IRsensor1Value = analogRead(IRSensor1Pin);
  float Voltage = IRsensor1Value * (3.3 / 4095.0);  // 4095 is max ADC value on ESP32
  return Voltage;
}

int IRsensorDetect2() {
  int IRsensor2Value = analogRead(IRSensor2Pin);
  float Voltage = IRsensor2Value * (3.3 / 4095.0);  // 4095 is max ADC value on ESP32
  return Voltage;
}

void buzzerAlarmOn() {
  digitalWrite(buzzerPin, HIGH);  // Turn buzzer ON
}
void buzzerAlarmOff() {
  digitalWrite(buzzerPin, LOW);  // Turn buzzer ON
}

void sendCaptureData(const String& AlertMessage, const String& messageCapture, const String& roomNumber) {
  if (WiFi.status() == WL_CONNECTED) {
    // Create an HTTPClient object
    HTTPClient http;

    // Start the connection
    http.begin(captureStatusURL);

    // Set content type to application/json
    http.addHeader("Content-Type", "application/json");

    // Create a JSON document to send
    DynamicJsonDocument jsonDoc(1024);
    jsonDoc["imageStatus"] = messageCapture;  // Add the "imageStatus" key with value "CAPTURE"
    jsonDoc["AlertMessage"] = AlertMessage;   // Add the "imageStatus" key with value "CAPTURE"
    jsonDoc["Room"] = roomNumber;             // Add the "imageStatus" key with value "CAPTURE"


    // Serialize JSON to string
    String jsonString;
    serializeJson(jsonDoc, jsonString);

    // Send POST request with the JSON body
    int httpResponseCode = http.POST(jsonString);

    // Check response from the server
    if (httpResponseCode > 0) {
      Serial.printf("POST Request sent successfully. Response code: %d\n", httpResponseCode);
    } else {
      Serial.printf("Error on sending POST request. HTTP Response code: %d\n", httpResponseCode);
    }

    // End the HTTP request
    http.end();
  } else {
    Serial.println("WiFi not connected!");
  }
}