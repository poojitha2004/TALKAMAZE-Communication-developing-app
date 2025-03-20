<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";  // Default XAMPP password
$database = "talkamaze";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Retrieve JSON data
$json = file_get_contents("php://input");
$data = json_decode($json, true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Validate inputs
if (empty($username) || empty($password)) {
    die(json_encode(["status" => "error", "message" => "All fields are required"]));
}

// Check user in database
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $hashed_password)) {
        echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Incorrect password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
