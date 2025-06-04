<?php
include 'config.php';

// Assume you have a database connection
$conn = mysqli_connect("$host", "$user", "$pass", "$db");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $id = $data['id'];
  $field = $data['field'];

  // Perform the increment in the database
  $stmt = $conn->prepare("UPDATE shows SET $field = $field + 1 WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Fetch the updated values from the database
  $stmt = $conn->prepare("SELECT season, episode FROM shows WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  // Return a JSON response
  echo json_encode([
    'success' => true,
    'updated' => $row
  ]);
}
?>
