
<?php
// Proxy script to forward requests to Arduino server
$url = 'http://192.168.0.103/check_connection';
$response = file_get_contents($url);

// Check if response is empty or false (indicating no connection or error)
if ($response === false) {
    // Set the response header and echo the JSON data for no connection found
    header('Content-Type: application/json');
    $data = array('status' => 'No connection found');
    echo json_encode($data);
} else {
    // Convert the response to JSON format and return it
    header('Content-Type: application/json');
    echo $response;
}

?>