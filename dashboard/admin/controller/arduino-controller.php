<?php
// Proxy script to forward requests to Arduino server
$url = 'http://192.168.1.112/check_connection';
$response = file_get_contents($url);

// Check if response is empty or false (indicating no connection or error)
if ($response === false) {
    // Set the response header and echo the JSON data for no connection found
    header('Content-Type: application/json');
    $data = array(
        'wifi_status' => 'No device found',
        'phLevel' => 0.0, // Default value for pH Level
        'turbidityLevel' => 0.0, // Default value for Turbidity Level
        'TDSLevel' => 0.0 // Default value for TDS Level
    );

    echo json_encode($data);
} else {
    // Convert the response to JSON format and return it
    header('Content-Type: application/json');
    echo $response;
}
?>
