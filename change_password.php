<?php
header("Content-Type: application/json");

// Ensure the file path is correct
require_once __DIR__ . "/db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

// Check if all fields are provided
if (!isset($data["email"], $data["old_password"], $data["new_password"], $data["confirm_password"])) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$email = $data["email"];
$old_password = $data["old_password"];
$new_password = $data["new_password"];
$confirm_password = $data["confirm_password"];

// Ensure new passwords match
if ($new_password !== $confirm_password) {
    echo json_encode(["status" => "error", "message" => "Passwords do not match"]);
    exit;
}

// Check if the user exists
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

// Verify old password
if (!password_verify($old_password, $user["password_hash"])) {
    echo json_encode(["status" => "error", "message" => "Incorrect old password"]);
    exit;
}

// Hash the new password securely
$new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);

// Update the password in the database
$update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$update_stmt->bind_param("ss", $new_password_hashed, $email);

if ($update_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password changed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update password"]);
}

// Close statements and database connection
$stmt->close();
$update_stmt->close();
$conn->close();
?>
