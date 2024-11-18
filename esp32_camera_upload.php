<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file'])) {
        // Define the upload directory
        $uploadDir = 'uploads/';

        // Generate a unique file name using the current timestamp and a random number (or use UUID)
        $uniqueId = uniqid('', true);  // Generates a unique ID based on the current time and a random component
        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);  // Get file extension
        $uploadFile = $uploadDir . $uniqueId . '.' . $fileExtension;  // Create a unique file name

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            echo 'File successfully uploaded with the name: ' . basename($uploadFile);
        } else {
            echo 'Error uploading file.';
        }
    } else {
        echo 'No file uploaded.';
    }
} else {
    echo 'Invalid request method.';
}
?>
