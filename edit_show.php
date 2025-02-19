<?php
include 'config.php';
include 'save_image.php';

// Fetch existing data
$show = [];
if (isset($_POST['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM shows WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    $show = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle update
if (isset($_POST['update'])) {
    $localImagePath = $_POST['existing_image'];
    
    // Handle new file upload
    if(!empty($_FILES['image_file']['name']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        // Delete old image
        if($localImagePath && file_exists(__DIR__ . '/' . $localImagePath)) {
            unlink(__DIR__ . '/' . $localImagePath);
        }
        $localImagePath = saveUploadedImage($_FILES['image_file']);
    }

    $stmt = $pdo->prepare("UPDATE shows SET
        name = ?,
        image_url = ?,
        local_image_path = ?,
        season = ?,
        episode = ?,
        link = ?,
        status = ?
        WHERE id = ?");
    
    $stmt->execute([
        $_POST['name'],
        $_POST['image_url'] ?? null,
        $localImagePath,
        $_POST['season'] ?? null,
        $_POST['episode'] ?? null,
        $_POST['link'] ?? null,
        $_POST['status'],
        $_POST['id']
    ]);

    // After update, redirect back to index
    header('Location: index.php');
    exit;
}

// Delete button click handler
if (isset($_POST['delete_show'])) {
    $stmt = $pdo->prepare("DELETE FROM shows WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header('Location: index.php');
    exit;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Show</title>
    <style>
    :root {
        --bg-color: #1a1a1a;
        --text-color: #ffffff;
        --hover-bg: #2a2a2a;
        --border-color: #404040;
        --delete-button-background: #ff4444;
    }
    body {
        background-color: var(--bg-color);
        color: var(--text-color);
        margin: 0;
        padding: 20px;
    }
    .container {
        max-width: 800px;
        margin: 0 auto;
        background-color: var(--hover-bg);
        border: 1px solid var(--border-color);
        padding: 20px;
        border-radius: 5px;
    }
    /* Include any other styles from index.php */

    .home-link {
            display: inline-block;
            margin-right: 20px;
            color:rgb(226, 19, 209);
            text-decoration: none;
            font-size: 2.5em;
            transition: color 0.3s;
        }
        .home-link:hover {
            color: #4CAF50;
        }
</style>
</head>
<body>
    <div class="container">
        <a href="/shows/index.php" class="home-link">Home →</a>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $show['id']; ?>">
            <input type="hidden" name="existing_image" value="<?= $show['local_image_path'] ?>">
            
            <h2>Edit Show</h2>
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $show['name']; ?>">
            </div><br>

            <div class="form-group">
                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url" value="<?php echo $show['image_url']; ?>">
            </div><br>

            <div class="form-group">
                <label for="season">Season:</label>
                <select id="season" name="season">
                    <option value="<?php echo $show['season']; ?>"><?php echo $show['season']; ?></option>
                </select>
            </div><br>

            <div class="form-group">
                <label for="episode">Episode:</label>
                <input type="text" id="episode" name="episode" value="<?php echo $show['episode']; ?>">
            </div><br>

            <div class="form-group">
                <label for="link">Link:</label>
                <input type="url" id="link" name="link" value="<?php echo $show['link']; ?>">
            </div><br>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="<?php echo $show['status']; ?>"><?php echo $show['status']; ?></option>
                </select><br>
            </div>
            <br>
            <div class="delete-button">
                <input type="submit" name="delete_show" value="Delete Show" style="background-color: var(--delete-button-background); color: white; border: none; padding: 8px 16px; cursor: pointer;">
                <input type="submit" name="update" value="Update Show" style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; cursor: pointer;">
            </div>
        </form>
    </div>
</body>
</html>
