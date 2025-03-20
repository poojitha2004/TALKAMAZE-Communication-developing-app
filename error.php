<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (empty)
$database = "talkamaze"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed", "modified_error" => $conn->connect_error]);
    exit;
}

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Check if required fields exist
    if (!isset($data['error'], $data['modified_error'])) {
        echo json_encode(["error" => "Missing required fields", "modified_error" => "error & modified_error are required"]);
        exit;
    }

    // Extract error details
    $error = trim($data['error']);
    $modified_error = trim($data['modified_error']);

    // Validate fields
    if (empty($error) || empty($modified_error)) {
        echo json_encode(["error" => "Validation failed", "modified_error" => "Both fields must be provided"]);
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO error_logs (error_message, modified_error) VALUES (?, ?)");
    $stmt->bind_param("ss", $error, $modified_error);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Error logged successfully"]);
    } else {
        echo json_encode(["error" => "Database error", "modified_error" => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
