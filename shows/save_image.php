<?php
function saveUploadedImage($file) {
    $targetDir = __DIR__ . "/images/";
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Validate file
    $allowedTypes = [
        'jpg' => 'image/jpeg', 
        'jpeg' => 'image/jpeg', 
        'png' => 'image/png', 
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];
    
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Check if valid file type
    if(!array_key_exists($fileType, $allowedTypes)) {
        die("Invalid file type. Allowed: JPG, JPEG, PNG, GIF, WEBP");
    }

    // Verify MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $detectedType = finfo_file($finfo, $file['tmp_name']);
    if(!in_array($detectedType, $allowedTypes)) {
        die("Invalid file content. Detected type: $detectedType");
    }

    // Generate unique filename
    $newFilename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $fileType;
    $targetPath = $targetDir . $newFilename;
    
    if(move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'images/' . $newFilename;
    }
    
    return null;
}
?>
