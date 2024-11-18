<?php
$targetDir = "uploads/";  // Directory to save uploaded images

// Check if the uploads directory exists, if not, create it
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Debug: Log the request method and all headers
file_put_contents("debug_log.txt", "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
file_put_contents("debug_log.txt", "Headers: " . json_encode(getallheaders()) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug: Check if files are uploaded
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = "capture_" . time() . ".jpg";  // Generate a unique name for the image
        $targetFilePath = $targetDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            echo "Image uploaded successfully!";
        } else {
            echo "Failed to upload image!";
        }
    } else {
        echo "No file uploaded!";
    }
} else {
    echo "Invalid request!";
}
?>
