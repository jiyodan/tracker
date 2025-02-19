<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Tracker</title>
    <style>
        :root {
            --bg-color:rgb(0, 0, 0);
            --text-color: #ffffff;
            --accent-color: #2a9fd6;
            --card-bg:rgb(22, 22, 22);
        }

        body {
            font-family: Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 2200px;
            margin: 0 auto;
        }

        .nav {
            margin-bottom: 20px;
            display: flex;
            gap: 75px;
        }

        .nav a {
            color: var(--text-color);
            text-decoration: none;
            padding: 2px 10px;
            border-radius: 5px;
            background-color: var(--card-bg);
        }

        .shows-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .show-card {
            background-color: var(--card-bg);
            padding: 5px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .show-card img {
            max-width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .show-card h3 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }

        .show-card div {
            flex: 1;
        }

        .add-form {
            background-color: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        input, select, button {
            padding: 8px;
            margin: 5px;
            background-color: #333;
            border: 1px solid #444;
            color: white;
            border-radius: 4px;
        }

        button {
            background-color: var(--accent-color);
            cursor: pointer;
        }
		
        .number-control {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 5px 0;
        }

        .increment-btn {
            padding: 2px 8px;
            margin: 0;
            background-color: #4CAF50;
        }

        .link-edit-form {
            margin-top: 8px;
            display: flex;
            gap: 1px;
        }

        .link-edit-input {
            flex: 1;
            padding: 1px;
            background-color: #444;
            border: 1px solid #555;
        }
    
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="?status=all">All</a>
            <a href="?status=watching">Watching</a>
            <a href="?status=planned">Planned</a>
            <a href="?status=paused">Paused</a>
            <a href="?status=completed">Completed</a>
            <a href="?status=dropped">Dropped</a>
        </div>

        <form class="add-form" method="POST" action="add_show.php" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Show Name" required>
            <input type="file" name="image_file" accept="image/*, .webp">
            <input type="text" name="image_url" placeholder="OR Image URL (if not uploading)">
            <input type="number" name="season" placeholder="Season">
            <input type="number" name="episode" placeholder="Episode">
            <input type="text" name="link" placeholder="Watch Link">
            <select name="status" required>
                <option value="watching">Watching</option>
                <option value="planned">Planned</option>
                <option value="paused">Paused</option>
                <option value="completed">Completed</option>
                <option value="dropped">Dropped</option>
            </select>
            <button type="submit">Add Show</button>
        </form>

        <?php
        $status = $_GET['status'] ?? 'all';
        $sql = "SELECT * FROM shows ORDER BY name ASC";
        if ($status !== 'all') {
            $sql = "SELECT * FROM shows WHERE status = ? ORDER BY name ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query($sql);
        }
        ?>

        <div class="shows-grid">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="show-card">
                    <?php if ($row['local_image_path'] || $row['image_url']): ?>
                        <img src="<?= htmlspecialchars($row['local_image_path'] ?? $row['image_url']) ?>" alt="Show image">
                    <?php endif; ?>
                    <div>
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
						
						                        <!-- Season with increment button -->
                        <div class="number-control">
                            <span>Season: <?= $row['season'] ?></span>
                            <form method="POST" action="increment.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="field" value="season">
                                <button type="submit" class="increment-btn">+</button>
                            </form>
                        </div>
						
						                        <!-- Episode with increment button -->
                        <div class="number-control">
                            <span>Episode: <?= $row['episode'] ?></span>
                            <form method="POST" action="increment.php" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="field" value="episode">
                                <button type="submit" class="increment-btn">+</button>
                            </form>
                        </div>
						
                        
                        <!-- Editable link field -->
                        <form class="link-edit-form" method="POST" action="update_link.php">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="text" class="link-edit-input" name="link" 
                                   value="<?= htmlspecialchars($row['link']) ?>">      
                            <button type="submit">Save</button>
                        </form>
						

                        <p>Status: <?= ucfirst($row['status']) ?></p>
                        <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">Watch Here</a>
                        <form method="POST" action="edit_show.php" style="margin-top: 8px;">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="submit">Edit</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
