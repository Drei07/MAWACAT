<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];

    // Ensure image was uploaded without error
    if ($image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($image['name']);
        
        if (move_uploaded_file($image['tmp_name'], $uploadFile)) {
            echo "Image uploaded successfully!";
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "File upload error!";
    }
} else {
    echo "No image data received.";
}
?>
