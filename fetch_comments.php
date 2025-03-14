<?php
require "config.php"; // Database connection

$course_id = intval($_GET["course_id"]);

$query = "SELECT * FROM comments WHERE course_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];

while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($comments);
?>
