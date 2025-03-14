<?php
// Database configuration
define('DB_HOST', 'localhost'); // Change this to your database host
define('DB_USER', 'root');      // Your database username
define('DB_PASS', '');  // Your database password
define('DB_NAME', 'project'); // Your database name

// hCaptcha Configuration


// Create Database Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check Connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Set Character Encoding
$conn->set_charset("utf8mb4");
?>
