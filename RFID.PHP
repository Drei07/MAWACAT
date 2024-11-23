<?php
// Database configuration
$host = 'localhost';
$username = 'root'; // Replace with your DB username
$password = ''; // Replace with your DB password
$database = 'RFID'; // Replace with your DB name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all UIDs
$sql = "SELECT uid FROM rfid_tags";
$result = $conn->query($sql);

// Initialize an array to hold the UIDs
$registeredUIDs = [];

// Fetch data and populate the array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $registeredUIDs[] = $row['uid'];
    }
} else {
    echo "No RFID tags found.";
}

// Close the connection
$conn->close();

// Format the array as a JSON structure
$response = [
    "registeredUID" => $registeredUIDs
];

// Output the JSON
header('Content-Type: application/json');
echo json_encode([$response]);
?>
