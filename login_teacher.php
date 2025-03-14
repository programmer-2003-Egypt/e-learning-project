
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>teachers_login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
      body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #f7f7f7, #c6e3f2);
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    animation: fadeIn 1s ease-in;

    .form-container {
        width: 30%;
        margin: auto;
        background: linear-gradient(45deg, grey, blue);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        animation: slideIn 1s ease-out, changeBackground 60s infinite;

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: white;
        }

        form {
            .input-group {
                margin-bottom: 20px;
                position: relative;

                label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                    font-size: 14px;
                    color: white;
                }

                input {
                    width: 100%;
                    padding: 12px;
                    font-size: 16px;
                    border: 2px solid #ccc;
                    border-radius: 8px;
                    box-sizing: border-box;
                    transition: border-color 0.3s ease, box-shadow 0.3s ease;

                    &:focus {
                        border-color: #007BFF;
                        outline: none;
                        box-shadow: 0 0 10px rgba(0, 123, 255, 0.6);
                    }
                }

                .error-message {
                    color: red;
                    font-size: 14px;
                    margin-top: 5px;
                    display: none;
                }
            }

            .submit-btn, .signup-btn, .upload-btn {
                display: inline-block;
                font-size: 18px;
                font-weight: bold;
                padding: 16px;
                border-radius: 10px;
                text-align: center;
                width: 100%;
                background: linear-gradient(90deg, #00e1ff, #0072ff);
                color: white;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease-in-out;
                box-shadow: 0 6px 18px rgba(0, 225, 255, 0.4);
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-top: 12px;
            }

            .submit-btn:hover, .signup-btn:hover, .upload-btn:hover {
                background: linear-gradient(90deg, #0072ff, #00e1ff);
                box-shadow: 0 12px 36px rgba(0, 225, 255, 0.8);
                transform: translateY(-4px);
            }

            .submit-btn:active, .signup-btn:active, .upload-btn:active {
                transform: translateY(1px);
                box-shadow: 0 6px 12px rgba(0, 225, 255, 0.4);
            }
        }
    }
}

// Move keyframes outside the main styles
@keyframes changeBackground {
    0% { background: linear-gradient(45deg, grey, black); }
    25% { background: linear-gradient(135deg, grey, black); }
    50% { background: linear-gradient(225deg, grey, black); }
    75% { background: linear-gradient(315deg, grey, black); }
    100% { background: linear-gradient(45deg, grey, black); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(30px); }
    to { transform: translateY(0); }
}
#image-preview {
    margin-top: 15px;
    width: 100%;
    height: 100%;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid #0072ff;
}
       
    </style>
</head>
<body>

<div class="form-container">
    <h2>Teacher Login</h2>
    <form id="validationForm" method="POST" action="action_teacher.php">
        <div class="input-group">
            <label for="name">Doctor Name:</label>
            <input type="text" id="username" name="name" placeholder="Enter doctor's name" required>
        </div>

        <div class="input-group">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" placeholder="Enter subject" required>
        </div>

        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <span class="error-message" id="emailError">Invalid email format</span>
        </div>

        <div class="input-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">
            <span class="error-message" id="passwordError">Password must be at least 4 characters</span>
        </div>

        <div class="input-group">
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
            <span class="error-message" id="phoneError"></span>
        </div>

        <!-- New Year Input Field -->
        <div class="input-group">
            <label for="year">Year:</label>
            <input type="number" id="year" name="year" placeholder="Enter year" required>
        </div>
        <div class="input-container">
    <label for="teacher_image" class="upload-btn">
        📷 رفع صورة
    </label>
    <input type="file" id="teacher_image" name="teacher_image" accept="image/*" onchange="previewImage(event)" hidden>
</div>
<img id="image-preview" alt="معاينة الصورة">

        <button type="submit" class="submit-btn">Submit</button>
        <a href="register_teacher.php">
            <button type="button" class="signup-btn">Sign Up as Teacher</button>
        </a>
    </form>
</div>



    <script>
        const form = document.getElementById('validationForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const phone = document.getElementById('phone');
      
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        
        form.addEventListener('submit', (e) => {
    e.preventDefault();

    let valid = true;

    
    if (valid) {
    form.submit(); // This will submit the form to action_teacher.php
}

});


function showError(input, errorMessage) {
    input.style.borderColor = 'red';
    errorMessage.style.display = 'block';
}

function hideError(input, errorMessage) {
    input.style.borderColor = '#ccc';
    errorMessage.style.display = 'none';
}
function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    </script>

</body>
</html>
