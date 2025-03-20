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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON input
    $json = file_get_contents('php://input');
    
    // Decode JSON data
    $data = json_decode($json, true);

    // Retrieve values from the decoded data
    $feedback = isset($data['feedback']) ? trim($data['feedback']) : '';

    // Validate required fields
    if (empty($feedback)) {
        die(json_encode(["status" => "error", "message" => "Feedback is required"]));
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO feedback (feedback_text) VALUES (?)");
    $stmt->bind_param("s", $feedback);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Feedback submitted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
