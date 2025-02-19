<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE shows SET link = ? WHERE id = ?");
    $stmt->execute([$_POST['link'], $_POST['id']]);
}

header('Location: index.php');
exit;
?>
