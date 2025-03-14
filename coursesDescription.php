<?php
include 'connect.php'; // Ensure this includes the correct file and path

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $description = mysqli_real_escape_string($conn, $_POST['description']); // Added description field
    $price = mysqli_real_escape_string($conn, $_POST['price']); // Added description field
    $date  = mysqli_real_escape_string($conn, $_POST['start_date']); // Added description field
    $doctor_name = isset($_GET['name']) ? urldecode($_GET['name']) : "Unknown Doctor"
    // Handle file upload
    $file_paths = [];
    if (isset($_FILES['files']) && $_FILES['files']['error'][0] == 0) {
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true); 
        }

        foreach ($_FILES['files']['tmp_name'] as $index => $tmp_name) {
            $file_name = $_FILES['files']['name'][$index];
            $file_path = 'uploads/' . basename($file_name); // Use basename to prevent directory traversal
            $file_paths[] = $file_path;

            if (move_uploaded_file($tmp_name, $file_path)) {
                // File uploaded successfully
            } else {
                echo "Failed to upload the file.";
                exit;
            }
        }
    }

    $file_paths_json = json_encode($file_paths); // Store file paths as JSON
    $stmt = $conn->prepare("INSERT INTO dynamiccourses (title, department, description, price, start_date,doctor_name) 
    VALUES (?, ?, ?, ?, ?)");

$stmt->bind_param("sssiss", $title, $department, $description, $price, $date,$doctor_name);



if ($stmt->execute()) {
    echo "<script>
        var courseAdded = true;
    </script>";
} else {
    echo "<script>
        var courseAdded = false;
        var errorMessage = '" . addslashes($stmt->error) . "';
    </script>";
}

$stmt->close();

}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="hello world">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كورس</title>
    <!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>


    <!-- jQuery CDN -->
   <!--  <script src="https://cdn.tiny.cloud/1/lv7bxnk3pgup2fk7m7dleumub4j5mmhx1c8b67u6gbwd9xjl/tinymce/5/tinymce.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/4.20.0/full/ckeditor.js"></script>
    <!-- TinyMCE Editor with extended plugins -->

    <script>
       
       document.addEventListener("DOMContentLoaded", function() {
            var element = document.getElementById('editor');

            // Check if the element exists before initializing CKEditor
            if (element) {
                CKEDITOR.replace('editor');

                // Ensure CKEditor is properly initialized
                CKEDITOR.instances['description'].on('instanceReady', function() {
                    console.log("CKEditor is ready.");
                });
            } else {
                console.error("CKEditor: Element #description not found.");
            }
        });
        // Handle file input with button click trigger
        function triggerFileInput() {
            document.getElementById('file-upload').click();
        }

        if (typeof courseAdded !== "undefined") {
    if (courseAdded) {
        Swal.fire({
            title: '✅ Success!',
            text: 'Course added successfully!',
            icon: 'success',
            confirmButtonText: '🎯 Great!',
            
            // 🎨 **Glassmorphism Effect**
            background: 'rgba(0, 0, 0, 0.7)', // Semi-transparent black
            color: '#fff', // White text
            backdrop: `
                rgba(0, 0, 0, 0.6) 
                url("https://source.unsplash.com/1600x900/?success,education") 
                center / cover no-repeat
            `, // **Background Image**
            
            // ✨ **More Custom Styles**
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            customClass: {
                confirmButton: 'swal-button-custom',
                popup: 'swal-popup-custom'
            },

            // ⏳ **Auto-Close**
            timer: 2000,
            timerProgressBar: true
        });
    } else {
        Swal.fire({
            title: '❌ Oops! Something Went Wrong',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: '🔄 Try Again',

            // 🎨 **Glassmorphism Effect**
            background: 'rgba(0, 0, 0, 0.85)', // Dark semi-transparent
            color: '#fff', // White text
            backdrop: `
                rgba(255, 0, 0, 0.2) 
                url("https://source.unsplash.com/1600x900/?error,warning") 
                center / cover no-repeat
            `, // **Background Image**
            
            // 🌀 **Animation Effects**
            showClass: {
                popup: 'animate__animated animate__shakeX' // **Shaking effect**
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },

            // ⚡ **Custom Styles**
            customClass: {
                confirmButton: 'swal-error-btn',
                popup: 'swal-error-popup'
            },

            // ⏳ **Auto-Close Option**
            timer: 2000,
            timerProgressBar: true
        });
    }
}

    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>
    
    <style>
        /* Base Styles */
        body {
    font-family: 'Arial', sans-serif;
    background: #f0f4f8;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 120vh;
    background: linear-gradient(135deg, #6c63ff, #ff6090);
    animation: backgroundAnimation 5s infinite alternate;
    overflow: scroll;
    color:white
}

@keyframes backgroundAnimation {
    0% {
        background: #6c63ff;
    }
    100% {
        background: rgb(20, 7, 118);
    }
}

.form-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: fit-content;
    transition: background-color 0.3s ease;
    border: 2px solid #007bff;
    animation: borderAnimation 2s infinite alternate;

    @keyframes borderAnimation {
        0% {
            border-color: #007bff;
        }
        100% {
            border-color: rgb(27, 67, 153);
        }
    }

    h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }
    

    .form-group {
        text-align: center;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);

            label {
        font-size: 16px;
        color: white; /* This sets the text color to dark gray */
        margin-bottom: 5px;
        display: block;
    }
    .swal {
    &-error {
        &-btn {
            background: linear-gradient(45deg, #ff0000, #ff6347);
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.8);
            transition: 0.3s;

            &:hover {
                background: linear-gradient(45deg, #ff6347, #ff0000);
                transform: scale(1.05);
            }
        }

        &-popup {
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(255, 0, 0, 0.6);
            padding: 20px;
        }
    }

    &-button {
        &-custom {
            background: linear-gradient(45deg, #32CD32, #00FF7F);
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;

            &:hover {
                background: linear-gradient(45deg, #00FF7F, #32CD32);
                transform: scale(1.05);
            }
        }
    }

    &-popup {
        &-custom {
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 255, 127, 0.8);
            padding: 20px;
        }
    }
}



        input,
        select,
        textarea {
            width: 95%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            cursor: pointer;
            background: transparent; /* Removed white background */
            color: #333;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
    }

    .file-section {
        .file-icon {
            display: inline-block;
            margin: 5px;
            padding: 10px;
            background: #eee;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }

        .file-link {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            &:hover {
                text-decoration: underline;
            }
        }
    }

    .slider-container {
        margin-top: 20px;
        text-align: center;

        #price-slider {
            width: 100%;
        }

        #price-display {
            font-size: 18px;
            color: #333;
        }
    }
    .cke_chrome {
            border-radius: 10px !important;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Custom Editor Background */
        .cke_editable {
            background-color: #f7f9fc !important;
            color: #333 !important;
            font-family: 'Arial', sans-serif !important;
            padding: 15px !important;
            border-radius: 5px !important;
        }

        /* Toolbar Customization */
        .cke_top {
            background: linear-gradient(to right, #007bff, #6610f2) !important;
            border-radius: 10px 10px 0 0 !important;
        }

        /* Change button styles */
        .cke_button {
            color: white !important;
        }

        /* Adjust dropdowns */
        .cke_combopanel {
            background: #fff !important;
            border-radius: 5px !important;
        }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background: #007bff;
        color: white;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;

        &:hover {
            background: #0056b3;
        }
    }

    #file-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* Custom Number Input */
    input[type="number"] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        background: transparent; /* Removed white background */

        &::-webkit-outer-spin-button,
        &::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }
    }

    #fileButton {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s;
        margin-bottom: 10px;

        &:hover {
            background-color: rgb(31, 101, 34);
        }
    }
}


    </style>
</head>
<body>
<div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-4">إضافة كورس جديد</h1>
    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
    <!-- Course Name -->
    <div class="flex flex-col">
        <label for="name" class="font-medium text-gray-700">اسم الكورس</label>
        <input type="text" name="name" id="name" required class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
    </div>

    <!-- Department -->
    <div class="flex flex-col">
        <label for="department" class="font-medium text-gray-700">القسم</label>
        <select name="department" id="department" required class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
            <option value="Physics">فيزياء</option>
            <option value="Computer Science">علوم الحاسوب</option>
        </select>
    </div>

    <!-- Course Description -->
    <div class="flex flex-col">
        <label for="description" class="font-medium text-gray-700">وصف الكورس</label>
        <textarea name="description" id="editor" placeholder="Write course description" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200"></textarea>
    </div>

    <!-- Price -->
    <div class="flex flex-col">
        <label for="price" class="font-medium text-gray-700">السعر</label>
        <input type="number" name="price" id="price" min="1" required class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
    </div>

    <!-- Course Image Upload -->
    <div class="flex flex-col">
        <label class="font-medium text-gray-700">صورة الكورس</label>
        <button type="button" onclick="document.getElementById('course-image').click()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">إختيار صورة</button>
        <input type="file" id="course-image" name="course_image" accept="image/*" class="hidden" onchange="previewImage(event)">
        <div id="image-preview" class="mt-3"></div>
    </div>

        <div class="flex flex-col">
            <label for="date-picker" class="font-medium text-gray-700">تاريخ بدء الكورس</label>
            <input type="date" id="date-picker" name="start_date" required class="border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-blue-200">
        </div>

        <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">إضافة الكورس</button>
    </form>
</div>

<script>
function previewImage(event) {
    let preview = document.getElementById("image-preview");
    let file = event.target.files[0];
    
    if (file) {
        let reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="mt-2 w-full h-full object-cover rounded-lg shadow-md">`;
        };
        reader.readAsDataURL(file);
    }
}
</script>

</body>
</html>
