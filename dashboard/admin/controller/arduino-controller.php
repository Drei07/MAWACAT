
<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use WebSocket\Client;

$webSocketUrl = 'ws://192.168.1.115:81/';

try {
    $client = new Client($webSocketUrl);

    // Receive response from the ESP32
    $response = $client->receive();

    // Check if response is empty or false (indicating no connection or error)
    if (empty($response)) {
        throw new Exception("No response from ESP32");
    }

    // Log successful connection to console
    error_log("WebSocket connection established and data received.");

    // Set the response header and return the JSON response
    header('Content-Type: application/json');
    echo $response;
} catch (Exception $e) {
    // Return a JSON response indicating no connection found
    header('Content-Type: application/json');
    echo json_encode([
        'wifi_status' => 'No device found',
        'phLevel' => 0.0, // Default value for pH Level
        'turbidityLevel' => 0.0, // Default value for Turbidity Level
        'TDSLevel' => 0.0 // Default value for TDS Level
    ]);

    // Log the error message to the console
    error_log("Failed to connect to Arduino server at $webSocketUrl: " . $e->getMessage());
}
?>