<?php
$host = "localhost";   // Change to your server host if different
$username = "root";    // Change to your database username
$password = "";        // Change to your database password
$database = "project"; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding to support special characters
$conn->set_charset("utf8mb4");

// Uncomment for debugging
// echo "Connected successfully!";
$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comment = $_POST["comment"] ?? "";
    $course_id = $_POST["course_id"] ?? "";
    $student_name = $_POST["student_name"] ?? "Anonymous";

    if (empty($comment) || empty($course_id)) {
        $response["message"] = "Invalid data!";
        echo json_encode($response);
        exit;
    }

    // Insert comment into database
    $stmt = $conn->prepare("INSERT INTO comments (course_id, student_name, comment, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $course_id, $student_name, $comment);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Comment added successfully!";
    } else {
        $response["message"] = "Database error!";
    }

    echo json_encode($response);
}
?>
