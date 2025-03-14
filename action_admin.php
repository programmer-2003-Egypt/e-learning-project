<?php
session_start();
require 'config.php';

$alertMessage = "";
$alertType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $admin_name = $_POST['admin_name'];

    if (empty($email) || empty($password) || empty($phone)) {
        $alertMessage = "All fields are required!";
        $alertType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alertMessage = "Invalid email format!";
        $alertType = "error";
    } else {
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $alertMessage = "Email or phone number already in use!";
            $alertType = "warning";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO admins (name,email, password, phone) VALUES (?, ?, ?)");
            $stmt->bind_param("ssss",$admin_name, $email, $hashedPassword, $phone);

            if ($stmt->execute()) {
                $alertMessage = "Admin registered successfully! Redirecting...";
                $alertType = "success";
            } else {
                $alertMessage = "Registration failed! Please try again.";
                $alertType = "error";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
function showSwal(message, type) {
    let icon = type; 
    let bgColor = {
        success: "#D4EDDA",
        error: "#F8D7DA",
        warning: "#FFF3CD",
        info: "#D1ECF1"
    }[type] || "#D1ECF1";

    Swal.fire({
        title: message,
        icon: icon,
        background: bgColor,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "OK",
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then((result) => {
        if (result.isConfirmed && type === "success") {
            window.location.href = "admin.php";
        }else{
            window.location.href = "login_admin.php";
        }
    });
}

// ✅ Show SweetAlert if PHP sets a message
window.onload = function() {
    let message = "<?php echo htmlspecialchars($alertMessage, ENT_QUOTES, 'UTF-8'); ?>";
    let type = "<?php echo htmlspecialchars($alertType, ENT_QUOTES, 'UTF-8'); ?>";
    if (message.trim() !== "") {
        showSwal(message, type);
    }
};
</script>

</body>
</html>
