<?php
// File to store the latest GPS data
$dataFile = 'latest_gps_data.json';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from GPS device and save it to the file
    $data = file_get_contents('php://input');
    $gpsDataArray = json_decode($data, true);
    $gpsDataArray['timestamp'] = time(); // Add a timestamp
    file_put_contents($dataFile, json_encode($gpsDataArray));
    echo 'GPS Data received';
} else {
    // Serve the latest GPS data
    if (file_exists($dataFile)) {
        $gpsData = json_decode(file_get_contents($dataFile), true);
        $currentTime = time();
        $dataAge = $currentTime - $gpsData['timestamp'];
        
        if ($dataAge > $timeoutDuration) {
            echo json_encode([
                'gps_status' => 'No GPS data found',
                'latitude' => 0.0,
                'longitude' => 0.0,
                'speed' => 0.0, // Default speed value
                'satellites' => 0 // Default number of satellites
            ]);
        } else {
            header('Content-Type: application/json');
            // Ensure speed and satellites are included if available
            if (isset($gpsData['speed'])) {
                $gpsData['speed'] = floatval($gpsData['speed']); // Convert to float if necessary
            }
            if (isset($gpsData['satellites'])) {
                $gpsData['satellites'] = intval($gpsData['satellites']); // Convert to integer if necessary
            }
            echo json_encode($gpsData);
        }
    } else {
        echo json_encode([
            'gps_status' => 'No GPS data found',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'speed' => 0.0, // Default speed value
            'satellites' => 0 // Default number of satellites
        ]);
    }
}
?>
