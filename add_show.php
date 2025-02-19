<?php
include 'config.php';
include 'save_image.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $localImagePath = null;
    
    // Handle file upload
    if(!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $localImagePath = saveUploadedImage($_FILES['image_file']);
    }

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO shows 
        (name, image_url, local_image_path, season, episode, link, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Execute with parameters
    $stmt->execute([
        $_POST['name'],
        $_POST['image_url'] ?? null,
        $localImagePath,
        $_POST['season'] ?? null,
        $_POST['episode'] ?? null,
        $_POST['link'] ?? null,
        $_POST['status']
    ]);
}
header('Location: index.php');
exit;
?>
