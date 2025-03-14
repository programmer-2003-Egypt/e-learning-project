<?php
include 'connect.php'; // Ensure this includes the correct file and path

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 $doctor_name = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Doctor"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
  

    // Handle file upload
    $file_path = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_path = 'uploads/' . $file_name;

        // Check if the uploads directory exists, and create it if not
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true); // Create folder if it doesn't exist
        }

        // Move the uploaded file to the correct folder
        if (move_uploaded_file($file_tmp, $file_path)) {
            // File uploaded successfully
        } else {
            echo "Failed to upload the file.";
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO courses (name, description, file_path,doctor_name) 
            VALUES ('$name', '$description', '$file_path','$doctor_name')";

    if ($conn->query($sql) === TRUE) {
        echo "Course added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كورس</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include SweetAlert2 library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateProgress(event) {
            if (event.lengthComputable) {
                let percent = (event.loaded / event.total) * 100;
                document.getElementById('progress-bar').style.width = percent + '%';
                document.getElementById('progress-text').innerText = Math.round(percent) + '%';
            }
        }

        function handleFileUpload(event) {
            let form = document.querySelector('form');
            let data = new FormData(form);

            let xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.upload.addEventListener('progress', updateProgress);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    Swal.fire({
                        title: 'تم الرفع بنجاح!',
                        text: 'تم رفع الملف بنجاح.',
                        icon: 'success',
                        confirmButtonText: 'موافق'
                    });
                } else {
                    Swal.fire({
                        title: 'فشل الرفع!',
                        text: 'حدث خطأ أثناء رفع الملف.',
                        icon: 'error',
                        confirmButtonText: 'حاول مرة أخرى'
                    });
                }
            };
            xhr.send(data);
            event.preventDefault();
        }

        // Trigger file input when the "Upload File" button is clicked
        function triggerFileInput() {
            document.getElementById('file').click();
        }
    </script>
</head>
<body class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 animate-gradient">
    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-lg animate-zoom-in">
        <h1 class="text-3xl font-extrabold text-center mb-6 text-gray-800">🚀 إضافة كورس جديد</h1>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="handleFileUpload(event)">
            <!-- Course Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">📌 اسم الكورس:</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    required 
                    class="block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">📝 وصف الكورس:</label>
    <textarea 
        name="description" 
        id="description" 
        required 
        class="block w-full p-3 border-2 border-gray-300 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y transition-all duration-300 textarea-style"
    ></textarea>
</div>

            <!-- File Upload -->
            <div class="file-upload-container text-center">
                <button 
                    type="button" 
                    class="block w-full py-3 px-5 bg-blue-600 text-white font-semibold rounded-lg shadow-lg hover:bg-blue-700 hover:scale-105 transition-transform animate-glow"
                    onclick="triggerFileInput()"
                >
                    📂 رفع ملف الكورس (اختياري)
                </button>
                <input 
                    type="file" 
                    name="file" 
                    id="file" 
                    class="hidden" 
                    onchange="handleFileSelection()"
                >
                <div id="file-info" class="mt-2 text-gray-600 hidden">
                    <span id="file-name"></span>
                    <button 
                        type="button" 
                        onclick="removeFile()" 
                        class="ml-2 text-red-500 text-sm font-semibold hover:text-red-700"
                    >
                        ❌ إزالة الملف
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-3 px-5 bg-purple-600 text-white font-bold rounded-lg shadow-lg hover:bg-purple-700 hover:scale-105 transition-transform animate-glow"
            >
                ➕ إضافة الكورس
            </button>
        </form>

       <!-- Progress Bar Container -->
<div class="relative w-full bg-gray-300 mt-6 rounded-full h-4 overflow-hidden shadow-lg">
    <!-- Animated Progress Bar -->
    <div id="progress-bar" 
        class="h-4 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full transition-all duration-500 ease-in-out relative"
        style="width: 0%;">
        <!-- Shimmer Effect -->
        <div class="absolute inset-0 bg-white opacity-20 blur-lg animate-shimmer"></div>
    </div>
</div>

<!-- Progress Text -->
<div id="progress-text" class="text-center mt-3 text-gray-800 font-bold text-lg tracking-wide">
    0%
</div>


    <style>
      /* Textarea Styles */
.textarea-style {
    background: white;
    min-height: 120px;
    font-size: 1rem;
    font-weight: 500;
    color: #333;
    transition: all 0.3s ease-in-out;
    border-radius: 10px;
    box-shadow: inset 0 2px 5px rgba(69, 3, 3, 0.1);
    padding: 10px;
    border: 2px solid transparent;

    &:focus {
        border-image: linear-gradient(45deg, rgb(0, 0, 0), rgb(8, 46, 113)) 1;
        border-width: 3px;
        box-shadow: 0 0 10px rgba(106, 17, 203, 0.5);
        
        &::placeholder {
            color: transparent;
        }
    }

    &:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
}

/* Progress Bar */
#progress-bar {
    box-shadow: 0 0 10px rgba(106, 17, 203, 0.5);
    
    /* Gradient shimmer animation */
    .animate-shimmer {
        animation: shimmer 1.5s infinite linear;
    }
}

/* Background Gradient Animation */
.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 8s ease infinite;
}

/* Keyframe Animations */
@keyframes shimmer {
    0% { opacity: 0.3; transform: translateX(-100%); }
    50% { opacity: 0.6; transform: translateX(0); }
    100% { opacity: 0.3; transform: translateX(100%); }
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes zoom-in {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Fade and Zoom */
.animate-zoom-in {
    animation: zoom-in 0.8s ease-out;
}

/* Button Glow Effect */
.animate-glow {
    animation: glow 2s infinite alternate;
}

@keyframes glow {
    0% { box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
    50% { box-shadow: 0 0 15px rgba(255, 255, 255, 0.6); }
    100% { box-shadow: 0 0 10px rgba(255, 255, 255, 0.3); }
}

/* Shake Effect */
.shake {
    animation: shake 0.4s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
}

    </style>

    <script>
        function triggerFileInput() {
            document.getElementById('file').click();
        }

        function handleFileSelection() {
            const fileInput = document.getElementById('file');
            const fileName = fileInput.files[0]?.name;
            const fileInfo = document.getElementById('file-info');
            const fileNameSpan = document.getElementById('file-name');

            if (fileName) {
                fileNameSpan.textContent = fileName;
                fileInfo.classList.remove('hidden');
            }
        }

        function removeFile() {
            const fileInput = document.getElementById('file');
            fileInput.value = ''; // Clear the file input
            const fileInfo = document.getElementById('file-info');
            fileInfo.classList.add('shake');
            setTimeout(() => {
                fileInfo.classList.add('hidden');
                fileInfo.classList.remove('shake');
            }, 400);
        }
    </script>
</body>
</html>
