<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];

    // Ensure an image file is provided
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Display the file name
        echo "File name: " . htmlspecialchars($image['name']);
    } else {
        echo "File upload error!";
    }
} else {
    echo "No image data received.";
}
?>
