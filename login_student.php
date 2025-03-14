<?php
$host = "localhost";
$dbname = "project";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ✅ Get student details (No validation)
        $name = trim($_POST['student_name'] ?? "Guest");
        $email = trim($_POST['student_email'] ?? "unknown@example.com");
        $phone = trim($_POST['student_phone'] ?? "0000000000");
        $year = trim($_POST['student_year'] ?? "1");
        $password = password_hash(trim($_POST['student_password'] ?? "defaultpass"), PASSWORD_DEFAULT);
        
        // ✅ Insert student directly into database
        $stmt = $pdo->prepare("INSERT INTO students (username, email, phone, year, password) VALUES (:username, :email, :phone, :year, :password)");
        $stmt->bindParam(':username', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // ✅ Redirect with name and email in URL
        header("Location: student.php?name=" . urlencode($name) . "&email=" . urlencode($email) . "&phone=" . urlencode($phone) . "&year=" . urlencode($year));
                
        exit();
    }
} catch (PDOException $e) {
    file_put_contents("error.txt", date('Y-m-d H:i:s') . " - Database error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo "❌ A system error occurred. Please try again later.";
}
?>



<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الطلاب</title>
    
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/lib/index.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
   
/* Global Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(180deg, #000, #333);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: bg-shift 5s infinite alternate ease-in-out;
}

@keyframes bg-shift {
    0% { background: linear-gradient(180deg, #000, #333); }
    100% { background: linear-gradient(180deg, #333, #000); }
}

.container-student-form {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(14px);
    width: 100%;
    max-width: 600px;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6);
    text-align: center;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.25);
    animation: fade-in 1.2s ease-in-out, floating 5s infinite ease-in-out;
    
    h1 {
        font-size: 32px;
        color: #fff;
        margin-bottom: 22px;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        animation: glow 1.5s infinite alternate ease-in-out;
    }
}

@keyframes glow {
    0% { text-shadow: 0 0 12px rgba(255, 255, 255, 0.6); }
    100% { text-shadow: 0 0 22px rgba(255, 255, 255, 1); }
}

.input-container {
    position: relative;
    margin-bottom: 22px;
    
    input {
        width: 100%;
        padding: 14px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.15);
        font-size: 17px;
        color: white;
        backdrop-filter: blur(10px);
        box-shadow: 5px 5px 12px rgba(0, 0, 0, 0.4);
        transition: all 0.3s ease-in-out;
        text-indent: 10px;
        
        &::placeholder {
            color: rgba(6, 245, 241, 0.6);
            transition: all 0.3s ease-in-out;
        }
        
        &:focus {
            outline: none;
            border: 1px solid #00e1ff;
            box-shadow: 0 0 18px #00e1ff;
            transform: scale(1.06);
            
            &::placeholder {
                color: transparent;
            }
        }
    }
}

.btn-submit, .upload-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(90deg, #00e1ff, #0072ff);
    color: white;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 6px 18px rgba(0, 225, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 1px;
    
    &:hover {
        background: linear-gradient(90deg, #0072ff, #00e1ff);
        box-shadow: 0 12px 36px rgba(0, 225, 255, 0.8);
        transform: translateY(-4px);
    }
    
    &:active {
        transform: translateY(1px);
        box-shadow: 0 6px 12px rgba(0, 225, 255, 0.4);
    }
}

.signup-link {
    color: rgba(255, 255, 255, 0.7);
    font-size: 18px;
    font-weight: bold;
    margin-top: 22px;
    
    a {
        color: #00e1ff;
        text-decoration: none;
        transition: color 0.3s ease-in-out;
        
        &:hover {
            color: #0072ff;
            text-decoration: underline;
        }
    }
}

.upload-btn {
    display: inline-block;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 20px;
    border-radius: 8px;
    text-align: center;
}

#image-preview {
    margin-top: 15px;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #0072ff;
}

.alert {
    padding: 10px;
    margin-top: 10px;
    text-align: center;
    border-radius: 5px;
    
    &.error {
        background: #ffcccc;
        color: red;
    }
}

@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .container-student-form {
        width: 95%;
        padding: 25px;
    }
}

    </style>
</head>

<body>

<div class="container-login-teacher">
<?php if (!empty($error)) { ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php } ?>
    <h1>تسجيل الدخول</h1>  
       
 
    <form method="post">
 
    
       <!-- Email Input -->
<div class="input-container">
    <input type="email" name="student_email" placeholder="من فضلك أدخل البريد الإلكتروني" required >
</div>

<!-- Password Input -->
<div class="input-container">
    <input type="password" name="student_password" placeholder="من فضلك أدخل كلمة المرور" id="password" required>
</div>
<div class="input-container">
    <input type="number" name="student_year" placeholder="من فضلك أدخل السنه الدراسيه" id="year" required>
</div>

<!-- Name Input -->
<div class="input-container">
    <input type="text" name="student_name" placeholder="أدخل اسمك" id="username" required>
</div>

<!-- Phone Number Input (Exactly 11 Digits) -->
<div class="input-container">
    <input type="tel" name="student_phone" id="phone" placeholder="أدخل رقم الهاتف" required 
          >
</div>
<div class="input-container">
    <label for="student_image" class="upload-btn">
        📷 رفع صورة
    </label>
    <input type="file" id="student_image" name="student_image" accept="image/*" onchange="previewImage(event)" hidden>
</div>
<img id="image-preview" alt="معاينة الصورة">


<!-- Submit Button -->
<button type="submit" class="btn-submit" name="login_student">تسجيل الدخول</button>

<!-- Signup Link -->
<div class="signup-student text-center bg-white/10 backdrop-blur-md shadow-lg rounded-xl p-5 mt-6 transition-all duration-300 hover:bg-white/20 hover:scale-105">
    <p class="text-lg font-semibold text-white/80">لم تسجل حتى الآن؟</p> 
    <a href="register_student.php" target="_blank" 
       class="mt-2 inline-block text-lg font-bold text-cyan-400 hover:text-blue-500 transition-all duration-300 underline">
        سجل بياناتك
    </a>
</div>


    </form>
</div>

<!-- JavaScript for Form Validation and SweetAlert2 -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");

        form.addEventListener("submit", function (e) {
            e.preventDefault();  // Prevent default form submission

            const fields = {
        email: document.querySelector("input[name='student_email']"),
        password: document.querySelector("input[name='student_password']"),
        name: document.querySelector("input[name='student_name']"),
        phone: document.querySelector("input[name='student_phone']")
    };

    const validations = [
        { field: fields.email, message: "من فضلك أدخل بريد إلكتروني صالح.", condition: !fields.email.value },
       /*  { field: fields.password, message: "من فضلك أدخل كلمة مرور تحتوي على 6 أحرف أو أكثر.", condition: fields.password.value.length < 6 }, */
        { field: fields.name, message: "من فضلك أدخل اسمك.", condition: !fields.name.value },
     /*    { field: fields.phone, message: "رقم الهاتف يجب أن يحتوي على 11 رقمًا.", condition: !/^\d{11}$/.test(fields.phone.value) } */
    ];

    for (let { field, message, condition } of validations) {
        if (condition) {
            Swal.fire({ icon: 'error', title: 'خطأ', text: message });
            field.focus();
            return false;
        }
    }

    document.querySelector(".upload-btn").addEventListener("click", function() {
        document.getElementById("student_image").click();
    });

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

            // If all validations pass
            Swal.fire({
                icon: 'success',
                title: 'تم',
                text: 'تم التحقق من المدخلات بنجاح.',
            }).then(() => {
                form.submit(); 
               
            });
        });
    });
</script>

</body>
</html>
