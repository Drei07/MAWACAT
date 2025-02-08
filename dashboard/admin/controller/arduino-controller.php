<?php

$proxyServerUrl = 'https://aquasense.online/dashboard/admin/controller/data.php'; // Replace with your proxy server URL
require_once '../authentication/admin-class.php';


$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'wifi_status' => 'No device found',
        'phLevel' => 0.0, // Default value for pH Level
        'turbidityLevel' => 0.0, // Default value for Turbidity Level
        'TDSLevel' => 0.0 // Default value for TDS Level
    ]);

    error_log("Failed to fetch data from proxy server.");
}


?>
