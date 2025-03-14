<?php
header("Content-Type: application/json");

try {
    // Get the raw POST data and decode JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    if (!isset($data["lecture_name"], $data["status"], $data["timestamp"], $data["video_stream"])) {
        throw new Exception("Missing required fields");
    }

    // Sanitize input data
    $lecture_name = trim(filter_var($data["lecture_name"], FILTER_SANITIZE_STRING));
    $status = trim(filter_var($data["status"], FILTER_SANITIZE_STRING));
    $timestamp = trim(filter_var($data["timestamp"], FILTER_SANITIZE_STRING));
    $video_stream = trim($data["video_stream"]); // Assume it's a video URL or base64 string

    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=project;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Check if columns exist, otherwise alter table
    $checkColumns = $pdo->query("SHOW COLUMNS FROM students LIKE 'video_stream'");
    if ($checkColumns->rowCount() == 0) {
        $pdo->exec("
            ALTER TABLE students 
            ADD COLUMN video_stream TEXT NULL;
        ");
    }

    // Insert data into database
    $query = "INSERT INTO students (lecture_name, status, timestamp, video_stream) 
              VALUES (:lecture_name, :status, :timestamp, :video_stream)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ":lecture_name" => $lecture_name,
        ":status" => $status,
        ":timestamp" => $timestamp,
        ":video_stream" => $video_stream
    ]);

    // Success response
    echo json_encode(["success" => true, "message" => "Notification sent successfully"]);

} catch (Exception $e) {
    // Log errors
    error_log("Error: " . $e->getMessage());

    // Return JSON error response
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
