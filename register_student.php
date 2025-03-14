<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل حساب جديد</title>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 for nice popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* Body styling */
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Form container */
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 120%;
           
            box-sizing: border-box;
        }

        /* Heading */
        h1 {
            text-align: center;
            color: #4e73df;
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Form inputs */
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        /* Input focus effect */
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #1cc88a;
            box-shadow: 0 0 10px rgba(28, 200, 138, 0.3);
        }

        /* Icon positioning inside input */
        .input-container {
            position: relative;
        }

        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4e73df;
        }

        input[type="file"] {
            display: none;
        }

        /* Button styling */
        .submit-btn {
            background-color: #1cc88a;
            color: white;
            padding: 15px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.2em;
            border: none;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #17a673;
        }

        /* Image preview */
        #photoPreview {
            max-width: 150px;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        #removePhoto {
            color: red;
            cursor: pointer;
            margin-top: 5px;
            display: none;
            text-align: center;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 1.8em;
            }

            .submit-btn {
                font-size: 1.1em;
            }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h1>تسجيل حساب جديد</h1>
        <form id="signupForm" method="post" enctype="multipart/form-data">
            <!-- Username -->
            <div class="input-container">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="اسم المستخدم" required>
            </div>

            <!-- Email -->
            <div class="input-container">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="البريد الإلكتروني" required>
            </div>

            <!-- Password -->
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
            </div>

            <!-- Confirm Password -->
            <div class="input-container">
                <i class="fas fa-lock"></i>
                <input type="password" id="cpassword" name="cpassword" placeholder="تأكيد كلمة المرور" required>
            </div>

            <!-- File Upload -->
            <div class="input-container">
                <button type="button" id="uploadButton" class="submit-btn">
                    <i class="fas fa-upload"></i> تحميل صورة
                </button>
                <input type="file" id="attachment" name="attachment">
            </div>

            <!-- Image Preview -->
            <div class="text-center">
                <img id="photoPreview" class="hidden" alt="Image Preview">
                <button type="button" id="removePhoto" class="hidden">إزالة الصورة</button>
            </div>

            <!-- Submit Button -->
            <input type="submit" value="تسجيل" class="submit-btn">
        </form>
    </div>

    <script>
        // Class for handling image preview
        class ImageHandler {
            constructor() {
                this.uploadButton = $('#uploadButton');
                this.attachmentInput = $('#attachment');
                this.photoPreview = $('#photoPreview');
                this.removePhotoButton = $('#removePhoto');
                this.init();
            }

            init() {
                this.uploadButton.on('click', () => this.attachmentInput.click());
                this.attachmentInput.on('change', (event) => this.previewImage(event));
                this.removePhotoButton.on('click', () => this.removeImage());
            }

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.photoPreview.attr('src', e.target.result).show();
                        this.removePhotoButton.show();
                    };
                    reader.readAsDataURL(file);
                }
            }

            removeImage() {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "هل تريد إزالة هذه الصورة؟",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.photoPreview.hide();
                        this.removePhotoButton.hide();
                        Swal.fire('تم!', 'تمت إزالة الصورة بنجاح.', 'success');
                    }
                });
            }
        }

        // Class for handling form validation
        class FormValidator {
            constructor() {
                this.form = $('#signupForm');
                this.init();
            }

            init() {
                this.form.on('submit', (e) => this.handleFormSubmit(e));
            }

            handleFormSubmit(e) {
                e.preventDefault(); // Prevent form from submitting the traditional way

                const username = $('#username').val();
                const email = $('#email').val();
                const password = $('#password').val();
                const cpassword = $('#cpassword').val();
                const file = $('#attachment')[0].files[0];

                let errors = [];
                if (password !== cpassword) {
                    errors.push("كلمتا المرور غير متطابقتين.");
                }

                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                if (!emailPattern.test(email)) {
                    errors.push("البريد الإلكتروني غير صالح.");
                }

                if (errors.length > 0) {
                    errors.forEach((error) => {
                        Swal.fire({ icon: 'error', title: 'خطأ', text: error });
                    });
                    return;
                }

                this.submitForm(username, email, password, cpassword, file);
            }

            submitForm(username, email, password, cpassword, file) {
                let formData = new FormData();
                formData.append('username', username);
                formData.append('email', email);
                formData.append('password', password);
                formData.append('cpassword', cpassword);
                if (file) {
                    formData.append('attachment', file);
                }

                $.ajax({
                    url: 'register_student.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: () => this.onFormSuccess(),
                    error: () => this.onFormError(),
                });
            }

            onFormSuccess() {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التسجيل بنجاح',
                    text: 'تمت عملية التسجيل بنجاح، سيتم تحويلك الآن.',
                }).then(() => {
                    window.location.href = 'main.php'; // Redirect on success
                });
            }

            onFormError() {
                Swal.fire({
                    icon: 'error',
                    title: 'حدث خطأ',
                    text: 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.'
                });
            }
        }

        // Initialize image handler and form validator
        const imageHandler = new ImageHandler();
        const formValidator = new FormValidator();
    </script>
</body>
</html>
