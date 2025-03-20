<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (empty)
$database = "talkamaze"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);  

    // Extract values
    $name = isset($data['name']) ? trim($data['name']) : '';
    $email = isset($data['email']) ? trim($data['email']) : '';
    $message = isset($data['message']) ? trim($data['message']) : '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute query and send response
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Message sent successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
