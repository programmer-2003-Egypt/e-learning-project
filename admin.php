<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("\u274c Connection failed: " . $conn->connect_error);
}

/* if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
} */
// Fetch doctors
// Handle DELETE for Doctors
if (isset($_POST['delete_doctor'])) {
    $id = $_POST['doctor_id'];
    $conn->query("DELETE FROM doctors WHERE id = $id");
    echo "<script>alert('Doctor deleted successfully!'); window.location.href='';</script>";
}

// Handle DELETE for Students
if (isset($_POST['delete_student'])) {
    $id = $_POST['student_id'];
    $conn->query("DELETE FROM students WHERE id = $id");
    echo "<script>alert('Student deleted successfully!'); window.location.href='';</script>";
}

// Handle UPDATE for Doctors
if (isset($_POST['update_doctor'])) {
    $id = $_POST['doctor_id'];
    $name = $_POST['doctor_name'];
    $subject = $_POST['doctor_subject'];
    $conn->query("UPDATE doctors SET name='$name', subject='$subject' WHERE id=$id");
    echo "<script>alert('Doctor updated successfully!'); window.location.href='';</script>";
}

// Handle UPDATE for Students
if (isset($_POST['update_student'])) {
    $id = $_POST['student_id'];
    $name = $_POST['student_name'];
    $year = $_POST['student_year'];
    $student_email = $_POST['student_email'];
    $conn->query("UPDATE students SET username='$name', email='$student_email',year='$student_year' WHERE id=$id");
    echo "<script>alert('Student updated successfully!'); window.location.href='';</script>";
}
// Fetch doctors
$doctors_result = $conn->query("SELECT * FROM doctors");
// Fetch students
$students_result = $conn->query("SELECT * FROM students");
// Function to sanitize input
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
function is_duplicate($conn, $email, $username, $phone) {
    // Check Teachers Table
    $query_teacher = "SELECT * FROM teachers WHERE email = ? OR username = ? OR phone = ?";
    $stmt_teacher = $conn->prepare($query_teacher);
    $stmt_teacher->bind_param("sss", $email, $username, $phone);
    $stmt_teacher->execute();
    $result_teacher = $stmt_teacher->get_result();
    
    if ($result_teacher->num_rows > 0) {
        return "teacher"; // Found duplicate in teachers table
    }

    // Check Students Table
    $query_student = "SELECT * FROM students WHERE email = ? OR username = ? OR phone = ?";
    $stmt_student = $conn->prepare($query_student);
    $stmt_student->bind_param("sss", $email, $username, $phone);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();
    
    if ($result_student->num_rows > 0) {
        return "student"; // Found duplicate in students table

    }

    return false; // No duplicate found
}

$message = ""; // Initialize message

// Handle Add Teacher
// Handle Add Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_teacher'])) {
    $email = clean_input($_POST['teacher_email']);
    $username = clean_input($_POST['teacher_username']);
    $phone_teacher = clean_input($_POST["teacher_number"]);
    $year_teacher = clean_input($_POST["teacher_year"]);
    $subject_teacher = clean_input($_POST["teacher_subject"]);
    $password_teacher = password_hash(clean_input($_POST['teacher_password']), PASSWORD_DEFAULT);

    $duplicate_check = is_duplicate($conn, $email, $username, $phone_teacher);

    if ($duplicate_check) {
        $message = "⚠️ Duplicate Entry! This email, username, or phone number is already in use by a $duplicate_check.";
        $alertType = "error"; // Set alert type
    } else {
        $stmt = $conn->prepare("INSERT INTO teachers (email, username, password, phone, year,subject) VALUES (?, ?, ?, ?, ?,?)");
        $stmt->bind_param("sssssi", $email, $username, $password_teacher, $phone_teacher, $year_teacher, $subject_teacher);

        $stmt->execute();
        $stmt->close();
        $message = "Teacher added successfully!";
    }
}

// Handle Add Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $email = clean_input($_POST['student_email']);
    $username = clean_input($_POST['student_username']);
    $phone_student = clean_input($_POST["student_number"]);
    $year_student = clean_input($_POST["student_year"]);
    $password_student = password_hash(clean_input($_POST['student_password']), PASSWORD_DEFAULT);

    $duplicate_check = is_duplicate($conn, $email, $username, $phone_student);

    if ($duplicate_check) {
        echo "<script>alert('\u26A0 Duplicate Entry! This email, username, or phone number is already in use by a $duplicate_check.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (email, username, password, phone, year) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $email, $username, $password_student, $phone_student, $year_student);
        $stmt->execute();
        $stmt->close();
        $message = "Student added successfully!";
    }
}
 /* $student = $students_result->fetch_assoc(); // Fetch first student
    $loginDateStudent = date("d M Y", strtotime($student['login_date']));
    $loginTimeStudent = date("h:i A", strtotime($student['login_date'])); */
   

?>

<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Zagazig University</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include Font Awesome CDN for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">

<h2 class="text-4xl font-extrabold text-blue-700 mb-6 animate-pulse text-center">🎓 Admin Panel</h2>

<div class="form-container w-full max-w-lg bg-white p-8 rounded-2xl shadow-2xl border border-gray-300">
    <!-- Add Teacher Form -->
    <form method="POST" class="mb-6 space-y-4">
        <h3 class="text-2xl font-semibold text-gray-700 flex items-center gap-2">👨‍🏫 Add Teacher</h3>
        <input type="email" name="teacher_email" placeholder="📧 Email" required class="form-input">
        <input type="text" name="teacher_username" placeholder="👨‍🏫 Username" required class="form-input">
        <input type="password" name="teacher_password" placeholder="🔑 Password" required class="form-input">
        <input type="number" name="teacher_number" placeholder="📞 Phone Number" required class="form-input">
        <input type="number" name="teacher_year" placeholder="📅 Year" required class="form-input">
        <input type="text" name="teacher_subject" placeholder="📖 Subject" required class="form-input">
        <button type="submit" name="add_teacher" class="action-btn primary">✅ Add Teacher</button>
    </form>

    <!-- Add Student Form -->
    <form method="POST" class="space-y-4">
        <h3 class="text-2xl font-semibold text-gray-700 flex items-center gap-2">🎓 Add Student</h3>
        <input type="email" name="student_email" placeholder="📧 Email" required class="form-input">
        <input type="text" name="student_username" placeholder="🎓 Username" required class="form-input">
        <input type="password" name="student_password" placeholder="🔑 Password" required class="form-input">
        <input type="number" name="student_number" placeholder="📞 Phone Number" required class="form-input">
        <input type="number" name="student_year" placeholder="📅 Year" required class="form-input">
        <button type="submit" name="add_student" class="action-btn secondary">✅ Add Student</button>
    </form>
     
</div>
<div class="table-container">
<!-- List of Doctors -->
<div class="flex justify-between items-center bg-gray-100 p-6 rounded-lg shadow-lg mt-6 mx-8 border border-gray-300">
    <div class="flex items-center space-x-4"> <!-- Increased space-x to 4 -->
        <i class="fas fa-chalkboard-teacher text-blue-500 text-3xl"></i> <!-- Increased icon size -->
        <h3 class="text-2xl font-bold text-gray-800">Doctors List</h3> <!-- Increased text size -->
        <button class="toggle-btn-doctors bg-blue-500 text-white px-6 py-3 rounded-full shadow-md hover:bg-green-700 transition ml-6 normal-case">
    <i class="fas fa-eye"></i> Toggle Doctors
</button>
    </div>
</div>



<div class="overflow-x-auto" id="table-container">
    <table class="w-full border-collapse mt-3 shadow-lg rounded-lg hidden">
        <thead>
            <tr class="bg-blue-600 text-white">
            <th class="border px-5 py-3">
                <i class="fas fa-id-badge text-blue-400 text-lg"></i> ID
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-user text-green-400 text-lg"></i> Name
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-book text-yellow-400 text-lg"></i> Subject
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-calendar-alt text-red-400 text-lg"></i> Year
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-envelope text-purple-400 text-lg"></i> Email
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-phone text-teal-400 text-lg"></i> Phone
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-calendar-alt text-green-500 text-lg"></i> Login Date
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-clock text-green-500 text-lg"></i> Login Time
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-cogs text-gray-400 text-lg animate-spin"></i> Actions
            </th>
        </thead>
        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
            <?php while ($doctor = $doctors_result->fetch_assoc()) { ?>
                <tr class="odd:bg-blue-50 even:bg-white hover:bg-blue-100 transition-all dark:odd:bg-gray-800 dark:even:bg-gray-900 dark:hover:bg-gray-700">
                    <form method="POST">
                        <td class="border px-5 py-3">
                        <input type="number" name="doctor_id" value="<?php echo $doctor['id']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300"></td>
                        <td class="border px-5 py-3">
                            <input type="text" name="doctor_name" value="<?php echo $doctor['name']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="text" name="doctor_subject" value="<?php echo $doctor['subject']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="number" name="doctor_year" value="<?php echo $doctor['year'] ?? ''; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="email" name="doctor_email" value="<?php echo $doctor['email']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="number" name="doctor_phone" value="<?php echo $doctor['phone_number']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300">
                        </td>
                        <td class="border px-5 py-3">
                        <?= date("d M Y", strtotime($doctor['login_date'])); ?>
                    </td>
                    <td class="border px-5 py-3">
                        <?= date("h:i A", strtotime($doctor['login_date'])); ?>
                    </td>
                        <td class="border px-5 py-3 flex justify-center gap-2">
                            <input type="hidden" name="doctor_id" value="<?php echo $doctor['id']; ?>">
                            <button type="submit" name="update_doctor" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">✏️ Update</button>
                            <button type="submit" name="delete_doctor" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition" onclick="return confirm('Are you sure?')">🗑️ Delete</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
   
<div class="table-container">
<div class="flex justify-between items-center bg-gray-100 p-6 rounded-lg shadow-lg mt-6 mx-8 border border-gray-300">
    <div class="flex items-center space-x-4"> <!-- Increased space-x to 4 -->
        <i class="fas fa-user-graduate text-green-500 text-3xl"></i> <!-- Increased icon size -->
        <h3 class="text-2xl font-bold text-gray-800">students List</h3> <!-- Increased text size -->
        <button class="toggle-btn-students bg-green-500 text-white px-6 py-3 rounded-full shadow-md hover:bg-blue-700 transition ml-6 normal-case">
    <i class="fas fa-eye"></i> Toggle Students
</button>

    </div>
</div>



<div class="overflow-x-auto" id="table-container">
    <table class="w-full border-collapse mt-3 shadow-lg rounded-lg hidden">
        <thead>
            <tr class="bg-green-600 text-white">
            <th class="border px-5 py-3">
                <i class="fas fa-id-badge text-blue-400 text-lg"></i> ID
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-user text-green-400 text-lg"></i> Name
            </th>
        
            <th class="border px-5 py-3">
                <i class="fas fa-calendar-alt text-red-400 text-lg"></i> Year
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-envelope text-purple-400 text-lg"></i> Email
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-phone text-teal-400 text-lg"></i> Phone
            </th>
             <th class="border px-5 py-3">
                <i class="fas fa-calendar-alt text-green-500 text-lg"></i> Login Date
            </th>
            <th class="border px-5 py-3">
                <i class="fas fa-clock text-green-500 text-lg"></i> Login Time
            </th>

            <th class="border px-5 py-3">
                <i class="fas fa-cogs text-gray-400 text-lg animate-spin"></i> Actions
            </th>
        </thead>
        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
            <?php while ($student = $students_result->fetch_assoc()) { ?>
                <tr class="odd:bg-green-50 even:bg-white hover:bg-green-100 transition-all dark:odd:bg-gray-800 dark:even:bg-gray-900 dark:hover:bg-gray-700">
                    <form method="POST">
                    <td class="border px-5 py-3">
                    <input type="number" name="student_id" value="<?php echo $student['id']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-blue-300"></td>
                        <td class="border px-5 py-3">
                            <input type="text" name="student_name" value="<?php echo $student['username']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="number" name="student_year" value="<?php echo $student['year']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="email" name="student_email" value="<?php echo $student['email']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        </td>
                        <td class="border px-5 py-3">
                            <input type="number" name="student_phone" value="<?php echo $student['phone']; ?>" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        </td>
                        <td class="border px-5 py-3">
                        <?= date("d M Y", strtotime($student['login_date'])); ?>
                    </td>
                    <td class="border px-5 py-3">
                        <?= date("h:i A", strtotime($student['login_date'])); ?>
                    </td>
                        <td class="border px-5 py-3 flex justify-center gap-2">
                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                            <button type="submit" name="update_student" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">✏️ Update</button>
                            <button type="submit" name="delete_student" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition" onclick="return confirm('Are you sure?')">🗑️ Delete</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</div>



<style>
   .date-time-cell {
    padding: 10px;
    text-align: center;
    font-family: "Arial", sans-serif;
    background-color: #F7FAFC; /* Light gray background */
    border-radius: 8px; /* Slightly rounded corners */
}

.date-label {
    font-weight: bold;
    color: #1E40AF; /* Dark Blue */
}

.time-label {
    font-weight: bold;
    color: #D97706; /* Amber */
}


    .table-container {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center horizontally */
    justify-content: center; /* Center vertically */
}

    .form-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    padding: 20px;
    background: #1f1f1f;
    border-radius: 15px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    margin: auto;
    animation: slideIn 0.8s ease-in-out;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;


    .form-input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #888;
        background: #f5f5f5;
        color: black;
        outline: none;
        transition: all 0.3s ease-in-out;
        font-size: 1rem;
        box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1);
        animation: fadeInUp 0.5s ease-in-out;

        &:focus {
            border-color: #4F46E5;
            box-shadow: 0px 0px 10px rgba(79, 70, 229, 0.3);
            background: #fff;
        }

        &:hover {
            border-color: #666;
        }

        &::placeholder {
            font-style: italic;
            transition: color 0.3s ease;
            color: blue;
            font-size:20px;
        }
        
        &:focus::placeholder {
            color: #666;
          
        }
    }

    .action-btn {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
        font-size: 1rem;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        border: none;
        transition: transform 0.3s, background 0.3s ease-in-out, box-shadow 0.3s;
        animation: bounceIn 0.8s ease-in-out;

        &.primary {
            background: #4F46E5;

            &:hover {
                transform: scale(1.05);
                filter: brightness(1.1);
            }

            &:active {
                transform: scale(0.98);
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
            }
        }

        &.secondary {
            background: #ff5722;

            &:hover {
                transform: scale(1.05);
                filter: brightness(1.1);
            }

            &:active {
                transform: scale(0.98);
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
            }
        }

        &.disabled {
            background: #888;
            cursor: not-allowed;
            opacity: 0.6;

            &:hover {
                transform: none;
                filter: none;
            }
        }
    }

    .form-footer {
    margin-top: 15px;
    font-size: 0.95rem;
    color: #888;
    text-align: center;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    gap: 15px;

    a {
        color: #4F46E5;
        text-decoration: none;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.3s ease-in-out;
        display: inline-block;

        &:hover {
            color: #6a5acd;
            text-decoration: underline;
            background: rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
        }

        &:first-child {
            background: #e0e7ff;
        }

        &:last-child {
            background: #d1fae5;
        }

        &.active {
            font-weight: bold;
            color: #fff;
            background: #4F46E5;
        }
    }

    @media (max-width: 650px) {
        flex-direction: column;

        a {
            width: 100%;
            text-align: center;
        }
    }
}

/* Keyframes Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes fadeInUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
@keyframes bounceIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

    </style>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Select all toggle buttons
    const toggleButtons = document.querySelectorAll(".toggle-btn-doctors, .toggle-btn-students");

    toggleButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Find the closest table after the button
            const targetTable = this.closest(".table-container")?.querySelector("table");

            if (targetTable) {
                targetTable.classList.toggle("hidden");
            }

            // Change button style on toggle
            this.classList.toggle("bg-red-500");
            this.classList.toggle("bg-purple-500");

            // Change icon inside button
            const icon = this.querySelector("i");
            if (icon) {
                icon.classList.toggle("fa-eye");
                icon.classList.toggle("fa-eye-slash");
            }
        });
    });
});

    
    <?php if (!empty($message)): ?>
        Swal.fire({
            title: "<?php echo ($alertType === 'error') ? 'Error ❌' : 'Success 🎉'; ?>",
            text: "<?php echo $message; ?>",
            icon: "<?php echo $alertType; ?>",
            confirmButtonText: "OK",
            confirmButtonColor: "#3085d6",
            background: "#f7f9fc",
            color: "#333",
            toast: false,
            position: "center",
            showClass: {
                popup: "animate__animated animate__fadeInDown"
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutUp"
            },
            allowOutsideClick: false,
            allowEscapeKey: true,
            timer: 3000,
            timerProgressBar: true
        });
    <?php endif; ?>

    function validateForm(event, formId) {
        event.preventDefault();
        let form = document.getElementById(formId);
        let inputs = form.querySelectorAll('.form-input');
        let isValid = true;

        form.querySelectorAll('.error').forEach(e => e.remove()); // Clear previous errors

        inputs.forEach(input => {
            if (input.value.trim() === '') {
                showError(input, "This field is required");
                isValid = false;
            }
        });

        // Email validation
        let email = form.querySelector("input[type='email']");
        if (email && !/^\S+@\S+\.\S+$/.test(email.value)) {
            showError(email, "Invalid email format");
            isValid = false;
        }

        // Password validation (Minimum 6 characters)
        let password = form.querySelector("input[type='password']");
        if (password && password.value.length < 6) {
            showError(password, "Password must be at least 6 characters");
            isValid = false;
        }

        // Phone number validation (At least 10 digits)
        let phone = form.querySelector("input[type='number'][id*='number']");
        if (phone && phone.value.length !== 11) {
    showError(phone, "Invalid phone number");
    isValid = false;
}


        // Year validation (Cannot be in the future)
        let year = form.querySelector("input[type='number'][id*='year']");
        let currentYear = new Date().getFullYear();
        if (year && (year.value < 1900 || year.value > currentYear)) {
            showError(year, "Enter a valid year");
            isValid = false;
        }

        if (isValid) {
            Swal.fire({
            title: "form submission",
            text: "form submitted successfully",
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#3085d6",
            background: "#f7f9fc",
            color: "#333",
            toast: false,
            position: "center",
            showClass: {
                popup: "animate__animated animate__fadeInDown"
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutUp"
            },
           
        });
            form.reset(); // Reset form on successful validation
        }
    }

    function showError(input, message) {
        let error = document.createElement("div");
        error.classList.add("error");
        error.innerText = message;
        input.parentNode.insertBefore(error, input.nextSibling);
    }

    document.getElementById("teacherForm").addEventListener("submit", (e) => validateForm(e, "teacherForm"));
    document.getElementById("studentForm").addEventListener("submit", (e) => validateForm(e, "studentForm"));
    
</script>
</body>
</html>
