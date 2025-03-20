<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (empty)
$database = "talkamaze"; // Database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Extract user details
$full_name = $data['full_name'] ?? '';
$date_of_birth = $data['date_of_birth'] ?? '';
$email = $data['email'] ?? '';
$mobile_number = $data['mobile_number'] ?? '';
$address = $data['address'] ?? '';

// Validate input
if (empty($full_name) || empty($date_of_birth) || empty($email) || empty($mobile_number) || empty($address)) {
    die(json_encode(["status" => "error", "message" => "All fields are required."]));
}

// Insert data into the database
$stmt = $conn->prepare("INSERT INTO user_profiles (full_name, date_of_birth, email, mobile_number, address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $full_name, $date_of_birth, $email, $mobile_number, $address);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile setup completed successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error saving profile."]);
}

// Close connections
$stmt->close();
$conn->close();
?>
