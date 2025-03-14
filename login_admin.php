<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
      /* Input focus effect */
input {
    &:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 10px rgba(96, 165, 250, 0.6);
    }
}

/* Shake animation */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
}

.shake {
    animation: shake 0.3s ease-in-out;
}

/* Button Styles */
.button {
    &.ripple {
        position: relative;
        overflow: hidden;

        &::after {
            content: "";
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            transform: scale(0);
            opacity: 0;
            transition: transform 0.6s, opacity 0.6s;
        }

        &:active::after {
            transform: scale(2);
            opacity: 1;
        }
    }

    &.loading {
        animation: pulse 1.2s infinite;
    }
}

/* Button Loading Animation */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
/* Form Container */
#loginForm {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;

    .relative {
        display: flex;
        flex-direction: column;

        label {
            font-size: 1.1rem;
            font-weight: bold;
            color: #374151; // Dark gray
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;

            &:hover {
                color: #3b82f6; // Blue on hover
            }
        }

        input {
            padding: 12px 16px;
            border: 2px solid #9ca3af;
            border-radius: 10px;
            outline: none;
            transition: all 0.3s ease;

            &:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 12px rgba(59, 130, 246, 0.5);
            }

            &:hover {
                background: #f3f4f6;
            }
        }
    }

    button {
        padding: 12px;
        border-radius: 10px;
        font-weight: bold;
        text-align: center;
        transition: transform 0.3s ease-in-out, background 0.3s;
        cursor: pointer;

        &:hover {
            transform: scale(1.05);
            background: #2563eb;
        }
    }
}
 /* Button Effects */
 #submitBtn {
        width: 100%;
        padding: 0.75rem;
        background: #3b82f6;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: transform 0.3s ease-in-out, background 0.3s;
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        overflow: hidden;
        position: relative;

        &:hover {
            background: #2563eb;
            transform: scale(1.05);
        }

        &:active {
            transform: scale(0.95);
        }

        &::after {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 10%, transparent 80%);
            top: -50%;
            left: -50%;
            transform: scale(0);
            transition: transform 0.5s;
        }

        &:active::after {
            transform: scale(1);
        }
    }


    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-900 text-white opacity-0">

    <div class="bg-gray-800 p-8 rounded-xl shadow-2xl w-full max-w-md border border-gray-700 scale-90 opacity-0">
        <h2 class="text-2xl font-bold text-center mb-4 flex items-center justify-center animate-bounce">
            🔐 <span class="ml-2">Admin Login</span>
        </h2>

        <form id="loginForm" class="space-y-6">
            <div class="relative">
                <label for="admin_name" class="text-lg font-semibold text-gray-700 mb-2 block transition-all duration-300">
                👤 Name
                </label>
                <input id="admin_name" type="text" name="name" placeholder="Enter your name" required
                    class="w-full px-4 py-3 text-black bg-gray-200 border border-gray-500 rounded-lg 
                    focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all duration-300 
                    focus:border-blue-500 focus:shadow-lg hover:bg-gray-100">
            </div>
    <!-- Email Field -->
    <div class="relative">
        <label for="email" class="text-lg font-semibold text-gray-700 mb-2 block transition-all duration-300">
            📧 Email
        </label>
        <input id="email" type="email" name="email" placeholder="Enter your email" required
            class="w-full px-4 py-3 text-black bg-gray-200 border border-gray-500 rounded-lg 
            focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all duration-300 
            focus:border-blue-500 focus:shadow-lg hover:bg-gray-100">
    </div>

    <div class="relative">
    <label for="password" class="text-lg font-semibold text-gray-700 mb-2 block transition-all duration-300">
        🔑 Password
    </label>
    <div class="relative">
        <input id="password" type="password" name="password" placeholder="Enter your password" required
            class="w-full px-4 py-3 text-black bg-gray-200 border border-gray-500 rounded-lg 
            focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all duration-300 
            focus:border-blue-500 focus:shadow-lg hover:bg-gray-100 pr-12">
        <button type="button" id="togglePassword" 
            class="absolute right-3 top-1/2 transform -translate-y-1/2 bg-gray-300 text-gray-700 
                   hover:bg-gray-400 hover:text-white p-2 rounded-full transition-all duration-300 focus:outline-none">
            👁️
        </button>
    </div>
</div>


    <!-- Phone Number Field -->
    <div class="relative">
        <label for="phone" class="text-lg font-semibold text-gray-700 mb-2 block transition-all duration-300">
            📞 Phone Number (11 digits)
        </label>
        <input id="phone" type="tel" name="phone" placeholder="Enter your phone number" required pattern="\d{11}"
            maxlength="11"
            class="w-full px-4 py-3 text-black bg-gray-200 border border-gray-500 rounded-lg 
            focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all duration-300 
            focus:border-blue-500 focus:shadow-lg hover:bg-gray-100">
    </div>

    <!-- Submit Button -->
    <button id="submitBtn" type="submit"
        class="w-full py-3 mt-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 
        text-white font-bold rounded-lg transition-transform transform hover:scale-105 duration-300 
        shadow-lg flex items-center justify-center gap-3 ripple">
        🚀 Login
    </button>
</form>

    </div>

    <!-- JavaScript for animations and validation -->
    <script>
        document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.textContent = "🙈"; // Change icon to hide
        } else {
            passwordField.type = "password";
            this.textContent = "👁️"; // Change icon to show
        }
    });
        // GSAP entrance animation
        document.addEventListener("DOMContentLoaded", () => {
            gsap.to("body", { opacity: 1, duration: 0.8 });
            gsap.to(".bg-gray-800", { opacity: 1, scale: 1, duration: 1, ease: "back.out(1.7)" });
        });

        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent actual form submission

            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let phone = document.getElementById("phone");
            let submitBtn = document.getElementById("submitBtn");

            let isValid = true;

            // Reset previous styles
            email.classList.remove("shake");
            password.classList.remove("shake");
            phone.classList.remove("shake");

            // Email validation
            if (!email.value.includes("@")) {
                email.classList.add("shake");
                showAlert("⚠️ Invalid Email!", "Please enter a valid email address.", "warning");
                isValid = false;
            }

            // Password length check
            if (password.value.length < 6) {
                password.classList.add("shake");
                showAlert("🔑 Weak Password!", "Password must be at least 6 characters.", "warning");
                isValid = false;
            }

            // Phone number check
            if (!/^\d{11}$/.test(phone.value)) {
                phone.classList.add("shake");
                showAlert("📞 Invalid Phone!", "Phone number must be 11 digits.", "error");
                isValid = false;
            }

            if (!isValid) return false;

            // Show loading effect on button
            submitBtn.innerHTML = `<span class="animate-spin text-xl">⏳</span> Logging in...`;
            submitBtn.classList.add("loading");
            submitBtn.disabled = true;

            // Show SweetAlert on Success
            setTimeout(() => {
                Swal.fire({
                    title: "✅ Login Successful!",
                    text: "Redirecting to Admin Panel...",
                    iconHtml: "🚀",
                    iconColor: "#4CAF50",
                    confirmButtonColor: "#4CAF50",
                    background: "#1F2937",
                    color: "#ffffff",
                    backdrop: `
                        linear-gradient(135deg, #667EEA, #764BA2)
                    `
                }).then(() => {
                    window.location.href = "admin.php";
                });
            }, 2000);
        });

        // Function to show alerts
        function showAlert(title, text, icon) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                iconColor: "#F59E0B",
                confirmButtonColor: "#F59E0B",
                background: "#1F2937",
                color: "#ffffff",
                backdrop: `
                    linear-gradient(135deg, #FF5733, #FFC300)
                `
            });
        }
    </script>

</body>
</html>
