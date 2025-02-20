<?php
$host = 'localhost';
$dbname = 'tracker';
$user = 'tracker';  // Default XAMPP username
$pass = 'password';       // Default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
//echo "connected"
?>
