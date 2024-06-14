<?php
// Directory to store the latest GPS data for each device
$dataDir = 'gps_data/';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the data directory exists
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    // Receive data from GPS device
    $data = file_get_contents('php://input');
    $gpsDataArray = json_decode($data, true);

    // Check for gps_id in the data
    if (isset($gpsDataArray['gps_id'])) {
        $deviceId = $gpsDataArray['gps_id'];
        $dataFile = $dataDir . $deviceId . '.json';
        $gpsDataArray['timestamp'] = time(); // Add a timestamp
        file_put_contents($dataFile, json_encode($gpsDataArray));
        echo 'GPS Data received';
    } else {
        echo 'GPS ID missing';
    }
} else {
    // Check if request is to get data for all devices
    if (isset($_GET['all_devices']) && $_GET['all_devices'] == 'true') {
        $allData = [];
        foreach (glob($dataDir . '*.json') as $filename) {
            $gpsData = json_decode(file_get_contents($filename), true);
            $currentTime = time();
            $dataAge = $currentTime - $gpsData['timestamp'];

            if ($dataAge <= $timeoutDuration) {
                $allData[] = $gpsData;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($allData);
    } else if (isset($_GET['gps_id'])) {
        // Serve the latest GPS data for the specified device
        $deviceId = $_GET['gps_id'];
        $dataFile = $dataDir . $deviceId . '.json';

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
                    'satellites' => 0, // Default number of satellites
                    'gps_id' => $deviceId // Include the GPS ID
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode($gpsData);
            }
        } else {
            echo json_encode([
                'gps_status' => 'No GPS data found',
                'latitude' => 0.0,
                'longitude' => 0.0,
                'speed' => 0.0, // Default speed value
                'satellites' => 0, // Default number of satellites
                'gps_id' => $deviceId // Include the GPS ID
            ]);
        }
    } else {
        echo 'Device ID missing';
    }
}
?>
