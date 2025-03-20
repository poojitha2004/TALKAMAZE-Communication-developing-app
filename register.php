<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (empty)
$database = "talkamaze"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $json = file_get_contents('php://input');
    
    // Decode JSON data
    $data = json_decode($json, true);  // Decodes as an associative array
    
    // Retrieve values from the decoded data
    $username = isset($data['username']) ? trim($data['username']) : '';
    $email = isset($data['email']) ? trim($data['email']) : '';
    $mobile = isset($data['mobile']) ? trim($data['mobile']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';
   // $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    //$email = isset($_POST['email']) ? trim($_POST['email']) : '';
    //$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';

   // $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate required fields
    if (empty($username) || empty($email) || empty($mobile) || empty($password)) {
        die("all fields required $username username, $email email, $mobile mobile, $password password");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, mobile, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $mobile, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
