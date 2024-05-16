<?php
// File to store the latest data
$dataFile = 'latest_data.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from ESP32 and save it to the file
    $data = file_get_contents('php://input');
    file_put_contents($dataFile, $data);
    echo 'Data received';
} else {
    // Serve the latest data
    if (file_exists($dataFile)) {
        header('Content-Type: application/json');
        echo file_get_contents($dataFile);
    } else {
        echo json_encode([
            'wifi_status' => 'No device found',
            'phLevel' => 0.0,
            'turbidityLevel' => 0.0,
            'TDSLevel' => 0.0
        ]);
    }
}
?>
