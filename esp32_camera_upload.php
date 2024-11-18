<?php
$targetDir = "uploads/";  // Directory to save uploaded images

// Check if the uploads directory exists, if not, create it
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
