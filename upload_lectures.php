<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video']) && isset($_POST['filename'])) {
    // Get the filename from the POST request
    $customFileName = $_POST['filename'];
    
    // Sanitize the custom filename (optional, to avoid security issues)
    $customFileName = preg_replace("/[^a-zA-Z0-9_\-]/", "", $customFileName); // Keeps only letters, numbers, underscores, and hyphens
     $doctor_name = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Doctor"
    
    // Define upload directory
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);  // Create directory if it doesn't exist
    }

    // Get the uploaded file extension
    $fileExtension = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
    
    // Create the file path using the custom filename
    $filePath = $uploadDir . $customFileName . '.' . $fileExtension;

    // Move the uploaded file to the desired location
    if (move_uploaded_file($_FILES['video']['tmp_name'], $filePath)) {
        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'project');

        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        // Insert the file information into the database
        $stmt = $conn->prepare("INSERT INTO lectures (filename, filepath,doctor_name) VALUES (?, ?,?)");
        $stmt->bind_param("sss", $customFileName, $filePath,$doctor_name);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'file' => $filePath]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database insertion failed']);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
