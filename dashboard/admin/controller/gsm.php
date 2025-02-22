<?php
// File to store the latest data
$dataFile = 'latest_data.json';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receive data from ESP32 and save it to the file
    $data = file_get_contents('php://input');
    $dataArray = json_decode($data, true);
    
    if (isset($dataArray['gpsCoordinates'])) {
        // Extract GPS data using regex
        preg_match('/Lat:\s*([-0-9.]+),\s*Lon:\s*([-0-9.]+),\s*Speed:\s*([0-9]+)\s*km\/h,\s*Satellites:\s*([0-9]+)/', 
                    $dataArray['gpsCoordinates'], $matches);

        $latitude = isset($matches[1]) ? $matches[1] : null;
        $longitude = isset($matches[2]) ? $matches[2] : null;
        $speed = isset($matches[3]) ? $matches[3] : null;
        $satellites = isset($matches[4]) ? $matches[4] : null;

        // Store extracted values in the JSON
        $dataArray['latitude'] = $latitude;
        $dataArray['longitude'] = $longitude;
        $dataArray['speed'] = $speed;
        $dataArray['satellites'] = $satellites;
    }

    $dataArray['timestamp'] = time(); // Add a timestamp
    file_put_contents($dataFile, json_encode($dataArray));
    echo 'Data received';
} else {
    // Serve the latest data
    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true);
        $currentTime = time();
        $dataAge = $currentTime - $data['timestamp'];
        
        if ($dataAge > $timeoutDuration) {
            echo json_encode([
                'wifi_status' => 'No device found',
                'gpsCoordinates' => 'No Coordinates',
                'latitude' => null,
                'longitude' => null,
                'speed' => null,
                'satellites' => null
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    } else {
        echo json_encode([
            'wifi_status' => 'No device found',
            'gpsCoordinates' => 'No Coordinates',
            'latitude' => null,
            'longitude' => null,
            'speed' => null,
            'satellites' => null
        ]);
    }
}
?>
