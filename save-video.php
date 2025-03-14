<?php
$servername = "localhost";
$username = "root"; // Change if using a different database user
$password = ""; // Add your database password if applicable
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['videoId']) && isset($data['videoTitle'])) {
    $videoUrl = "https://www.youtube.com/watch?v=" . $data['videoId'];
    $videoTitle = $conn->real_escape_string($data['videoTitle']);

    $sql = "INSERT INTO videos (video_url, video_title) VALUES ('$videoUrl', '$videoTitle')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Video saved successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}

$conn->close();
?>
