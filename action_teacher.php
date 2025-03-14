<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $phone = $conn->real_escape_string($_POST['phone']);
    $year = $conn->real_escape_string($_POST['year']);

    // Handle Image Upload
    $imagePath = "";
    if (isset($_FILES["teacher_image"]) && $_FILES["teacher_image"]["error"] == 0) {
        $imageDir = "uploads/";
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES["teacher_image"]["name"]);
        $imagePath = $imageDir . $imageName;
        move_uploaded_file($_FILES["teacher_image"]["tmp_name"], $imagePath);
    }

    // Insert into doctors table
    $sql = "INSERT INTO doctors (name, email, subject, password, phone_number, img) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $subject, $password, $phone, $imagePath);

    if ($stmt->execute()) {
        $message = "Login successful! Redirecting...";
        $alertType = "success";
        $redirect = "teacher.php?name=" . urlencode($name) . "&email=" . urlencode($email);
    } else {
        $message = "Database error! Unable to save data.";
        $alertType = "error";
    }

    // Close the statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";
$alertType = "";
$redirect = "";
$name = "";
$email = "";
$subject = "";
$phone = "";
$year = "";
$imagePath = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $conn->real_escape_string($_POST['phone']);
    $year = $conn->real_escape_string($_POST['year']);

    // Handle Image Upload
    if (isset($_FILES["teacher_image"]) && $_FILES["teacher_image"]["error"] == 0) {
        $imageDir = "uploads/";
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES["teacher_image"]["name"]);
        $imagePath = $imageDir . $imageName;
        move_uploaded_file($_FILES["teacher_image"]["tmp_name"], $imagePath);
    }

    // Check if teacher exists
    $query_teacher = "SELECT * FROM teachers WHERE name = ? AND email = ? AND subject = ? AND phone = ? AND year = ?";
    $stmt_teacher = $conn->prepare($query_teacher);
    $stmt_teacher->bind_param("sssss", $name, $email, $subject, $phone, $year);
    $stmt_teacher->execute();
    $result_teacher = $stmt_teacher->get_result();

    if ($result_teacher->num_rows == 0) {
        $message = "No matching teacher found. Please check your details and try again.";
        $alertType = "error";
    } else {
        // Fetch teacher details
        $teacher = $result_teacher->fetch_assoc();

        // Insert into doctors table
        $sql = "INSERT INTO doctors (name, email, subject, password, phone_number, img) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $subject, $teacher['password'], $phone, $imagePath);

        if ($stmt->execute()) {
            $message = "Login successful! Redirecting...";
            $alertType = "success";
            header("Location: teacher.php?name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&year=" . urlencode($year) . "&subject=" . urlencode($subject));
            exit();            
        } else {
            $message = "Database error! Unable to save data.";
            $alertType = "error";
        }
        $stmt->close();
    }

    $stmt_teacher->close();
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    <?php if (isset($message)): ?>
        Swal.fire({
            icon: '<?php echo $alertType; ?>',
            title: '<?php echo ($alertType == "success") ? "Success" : "Error"; ?>',
            text: '<?php echo $message; ?>',
            confirmButtonColor: '<?php echo ($alertType == "success") ? "#28a745" : "#dc3545"; ?>', // Green for success, red for error
            confirmButtonText: "OK",
            backdrop: `
                rgba(0,0,0,0.5) 
                center top
                no-repeat
            `,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            timer: 5000, // Auto close after 5 seconds
            timerProgressBar: true,
        })
        <?php if (isset($redirect)): ?>
            .then(() => {
                window.location.href = '<?php echo $redirect; ?>';
            });
        <?php endif; ?>
    <?php endif; ?>
</script>

</body>
</html>
