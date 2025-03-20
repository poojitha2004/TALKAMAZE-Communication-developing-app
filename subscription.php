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
    // Retrieve JSON data from request
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);  // Decodes as an associative array

    // Retrieve subscription details
    $course = isset($data["course"]) ? trim($data["course"]) : "";
    $duration = isset($data["duration"]) ? trim($data["duration"]) : "";
    $mode_of_payment = isset($data["mode_of_payment"]) ? trim($data["mode_of_payment"]) : "";
    $payment_history = isset($data["payment_history"]) ? trim($data["payment_history"]) : "";

    // Validate required fields
    if (empty($course) || empty($duration) || empty($mode_of_payment) || empty($payment_history)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO subscriptions (course, duration, mode_of_payment, payment_history) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $course, $duration, $mode_of_payment, $payment_history);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Subscription registered successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
