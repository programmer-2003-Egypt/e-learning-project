<?php
// save-video.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video_id = $_POST['video_id'];
    $title = $_POST['title'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'project');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO videos (video_url, video_title) VALUES (?, ?)");
    $stmt->bind_param("ss", $video_id, $title);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save']);
    }

    $stmt->close();
    $conn->close();
}
?>
