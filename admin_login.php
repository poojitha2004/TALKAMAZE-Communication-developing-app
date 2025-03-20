<?php
header("Content-Type: application/json");
require_once __DIR__ . "/db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"] ?? null;
$email = $data["email"] ?? null;
$password = $data["password"] ?? null;

if (!$username || !$email || !$password) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Debugging: Print received values
error_log("Received: Username=$username, Email=$email");

// Check if admin exists
$stmt = $conn->prepare("SELECT password_hash FROM admins WHERE username = ? AND email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo json_encode(["status" => "error", "message" => "Admin not found"]);
    exit;
}

// Verify password
if (!password_verify($password, $admin["password_hash"])) {
    echo json_encode(["status" => "error", "message" => "Incorrect password"]);
    exit;
}

echo json_encode(["status" => "success", "message" => "Admin login successful"]);

$stmt->close();
$conn->close();
?>
