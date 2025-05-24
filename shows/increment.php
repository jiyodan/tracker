<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field'];
    $validFields = ['season', 'episode'];
    
    if(in_array($field, $validFields)) {
        $stmt = $pdo->prepare("UPDATE shows SET $field = $field + 1 WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
}

header('Location: index.php?status=watching');
exit;
?>
