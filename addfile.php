<?php
$con = new PDO("mysql:host=localhost;dbname=project", "root", "");

// التحقق من طلب الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_file'])) {
    $folder_id = $_POST['folder_id'];
    $term = trim($_POST['term']);
    
    // جلب اسم المجلد والمستوى الأكاديمي
    $folder_stmt = $con->prepare("SELECT folder_name, academic_level FROM folders WHERE id = :folder_id");
    $folder_stmt->execute([':folder_id' => $folder_id]);
    $folder = $folder_stmt->fetch(PDO::FETCH_ASSOC);

    if ($folder) {
        $upload_dir = "uploads/" . trim($folder['academic_level']) . "/" . trim($folder['folder_name']) . "/" . trim($term) . "/";

        // إنشاء المجلد إذا لم يكن موجودًا
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                die("فشل في إنشاء المجلد. تأكد من الصلاحيات.");
            }
        }

        // معالجة الملفات المرفوعة
        foreach ($_FILES['files']['name'] as $key => $file_name) {
            if ($_FILES['files']['error'][$key] == 0) {
                $file_tmp = $_FILES['files']['tmp_name'][$key];
                $file_path = $upload_dir . basename($file_name);

                // نقل الملف إلى المجلد المحدد
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // إدخال بيانات الملف في قاعدة البيانات
                    $stmt = $con->prepare("INSERT INTO files (file_name, folder_id, term) VALUES (:file_name, :folder_id, :term)");
                    $stmt->execute([':file_name' => $file_name, ':folder_id' => $folder_id, ':term' => $term]);
                } else {
                    echo "فشل في رفع الملف: $file_name.<br />";
                }
            } else {
                echo "خطأ في رفع الملف: $file_name.<br />";
            }
        }

        echo "تم إضافة الملفات بنجاح.";
    } else {
        die("المجلد غير موجود.");
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📂 إضافة ملفات جديدة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        /* 🎭 Page Load Animation */
        body {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        body.loaded {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg max-w-lg transform transition duration-500 hover:scale-105">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center animate-pulse">📂 إضافة ملفات جديدة</h1>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Folder Selection -->
            <div class="transition duration-300 transform hover:scale-105">
                <label class="block text-gray-700 font-semibold mb-1">📁 اختر المجلد:</label>
                <select name="folder_id" required 
                    class="border border-gray-300 p-3 rounded-lg w-full focus:ring focus:ring-green-300 transition">
                    <?php
                    $folders = $con->query("SELECT id, folder_name, academic_level FROM folders")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($folders as $folder) {
                        echo "<option value='{$folder['id']}'>({$folder['academic_level']}) {$folder['folder_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Term Selection -->
            <div class="transition duration-300 transform hover:scale-105">
                <label class="block text-gray-700 font-semibold mb-1">📅 اختر الترم:</label>
                <select name="term" required 
                    class="border border-gray-300 p-3 rounded-lg w-full focus:ring focus:ring-green-300 transition">
                    <option value="الأول">الأول</option>
                    <option value="الثاني">الثاني</option>
                </select>
            </div>

            <!-- Custom Upload Button -->
            <div class="text-center">
                <input type="file" id="fileInput" name="files[]" multiple hidden>
                <button type="button" id="customUploadBtn"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg shadow-md font-bold hover:bg-blue-600 transition-all duration-300 transform hover:scale-110">
                    📎 اختر الملفات
                </button>
                <p id="fileList" class="text-sm text-gray-600 mt-2"></p>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="add_file" id="submitBtn"
                class="w-full bg-green-500 text-white py-3 rounded-lg shadow-md font-bold hover:bg-green-600 transition-all duration-300 transform hover:scale-110 opacity-50 cursor-not-allowed"
                disabled>
                🚀 إضافة الملفات
            </button>
        </form>
    </div>

    <!-- Dark Mode Button -->
    <button id="themeToggle"
        class="fixed top-4 right-6 bg-gray-800 text-white px-4 py-2 rounded-full shadow-md transition-all duration-500 hover:scale-110">
        🌙 Toggle Dark Mode
    </button>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 🎭 Page Load Animation
            document.body.classList.add("loaded");

            const fileInput = document.getElementById("fileInput");
            const customUploadBtn = document.getElementById("customUploadBtn");
            const fileList = document.getElementById("fileList");
            const submitBtn = document.getElementById("submitBtn");

            // 📂 Custom Upload Button Click Event
            customUploadBtn.addEventListener("click", function () {
                fileInput.click();
            });

            // ⚡ Show Selected Files with Animation
            fileInput.addEventListener("change", function () {
                let files = this.files;
                fileList.innerHTML = "";

                if (files.length > 0) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove("opacity-50", "cursor-not-allowed");

                    for (let file of files) {
                        let fileType = file.name.split('.').pop().toLowerCase();
                        let icon = "📄"; // Default file icon
                        
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) icon = "🖼️";
                        if (['pdf'].includes(fileType)) icon = "📕";
                        if (['doc', 'docx'].includes(fileType)) icon = "📄";
                        if (['zip', 'rar'].includes(fileType)) icon = "📦";

                        let fileItem = document.createElement("p");
                        fileItem.innerHTML = `${icon} ${file.name}`;
                        fileItem.classList.add("animate-fade-in");
                        fileList.appendChild(fileItem);
                    }
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add("opacity-50", "cursor-not-allowed");
                }
            });

            // 🎨 Dark Mode Toggle
            const themeToggle = document.getElementById("themeToggle");
            themeToggle.addEventListener("click", function () {
                document.body.classList.toggle("bg-gray-900");
                document.body.classList.toggle("text-white");
                themeToggle.innerHTML = document.body.classList.contains("bg-gray-900") ? "☀️ Light Mode" : "🌙 Dark Mode";
            });
        });

        // ✨ Add fade-in animation
        document.styleSheets[0].insertRule(`
            @keyframes fade-in {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `, 0);

        document.styleSheets[0].insertRule(`
            .animate-fade-in {
                animation: fade-in 0.5s ease-in-out;
            }
        `, 0);
    </script>
</body>
</html>
