<?php
$servername = "localhost";
$username = "root";
$password = "";  // Default XAMPP password
$database = "talkamaze";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = "testuser";  // Change if needed

$stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();

echo "Stored Hash: " . $hashed_password;

$stmt->close();
$conn->close();
?>
