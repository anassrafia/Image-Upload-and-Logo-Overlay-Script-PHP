<?php
// Set the logo path
$logoPath = 'https://360annonces.com/assets/img/360annoncesL.png';

// Directory to save uploaded images
$uploadDir = 'images/';

// Ensure the upload directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle the file uploads
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['images'])) {
    // Loop through each uploaded file
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $uploadFile = $uploadDir . basename($_FILES['images']['name'][$key]);
        
        if (move_uploaded_file($tmp_name, $uploadFile)) {
            // Load the uploaded image
            $uploadedImage = @imagecreatefromstring(file_get_contents($uploadFile));
            
            if ($uploadedImage !== false) {
                // Load the logo image
                $logoImage = @imagecreatefrompng($logoPath);
                
                if ($logoImage !== false) {
                    // Get dimensions of the uploaded image
                    $uploadedWidth = imagesx($uploadedImage);
                    $uploadedHeight = imagesy($uploadedImage);
                    
                    // Get original dimensions of the logo
                    $logoWidth = imagesx($logoImage);
                    $logoHeight = imagesy($logoImage);
                    
                    // Calculate new dimensions for the logo (50% of the original size)
                    $newLogoWidth = $logoWidth / 2;
                    $newLogoHeight = $logoHeight / 2;
                    
                    // Create a new true color image with the new dimensions
                    $resizedLogoImage = imagecreatetruecolor($newLogoWidth, $newLogoHeight);
                    
                    // Preserve transparency
                    imagealphablending($resizedLogoImage, false);
                    imagesavealpha($resizedLogoImage, true);
                    
                    // Resize the logo image
                    imagecopyresampled($resizedLogoImage, $logoImage, 0, 0, 0, 0, $newLogoWidth, $newLogoHeight, $logoWidth, $logoHeight);
                    
                    // Calculate position to center the resized logo
                    $logoX = ($uploadedWidth - $newLogoWidth) / 2;
                    $logoY = ($uploadedHeight - $newLogoHeight) / 2;
                    
                    // Merge the resized logo onto the uploaded image
                    imagecopy($uploadedImage, $resizedLogoImage, $logoX, $logoY, 0, 0, $newLogoWidth, $newLogoHeight);
                    
                    // Save the new image
                    imagejpeg($uploadedImage, $uploadFile);
                    
                    // Clean up
                    imagedestroy($uploadedImage);
                    imagedestroy($logoImage);
                    imagedestroy($resizedLogoImage);
                    
                    echo "Image {$_FILES['images']['name'][$key]} uploaded and logo merged successfully!<br>";
                } else {
                    echo "Failed to load the logo image for {$_FILES['images']['name'][$key]}.<br>";
                }
            } else {
                echo "Failed to load the uploaded image {$_FILES['images']['name'][$key]}.<br>";
            }
        } else {
            echo "Failed to upload the file {$_FILES['images']['name'][$key]}.<br>";
        }
    }
}
?>