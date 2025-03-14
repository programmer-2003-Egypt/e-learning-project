
<?php
session_start();
include 'connect.php'; // Include database connection file

// Check if AJAX request is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data['action'] === "logout") {
        if (!isset($_SESSION['student_id'])) {
            echo json_encode(["success" => false, "message" => "No teacher logged in"]);
            exit;
        }

        $teacher_id = $_SESSION['studenr_id']; // Get teacher ID from session

        // Remove teacher from database
        $stmt = $conn->prepare("DELETE FROM studnets WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            session_destroy(); // Destroy session after removing teacher
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to remove teacher"]);
        }

        $stmt->close();
        $conn->close();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>courses</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- SweetAlert2 for Confirmation Dialog -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
  /* 🎨 Global Styles */
  body {
    font-family: "Poppins", Arial, sans-serif;
    margin: 0;
    background-color: #f3f4f6;
    transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
}

/* 🔥 Header Section */
#header {
    background: linear-gradient(135deg, #4c6ef5, #2d87f0);
    color: white;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-bottom: 3px solid rgba(255, 255, 255, 0.2);
    animation: fadeIn 1s ease-in-out;

    h1 {
        font-size: 2.2rem;
        font-weight: bold;
        letter-spacing: 1px;
        animation: slideDown 0.8s ease-in-out;
    }

}

/* 🎨 Icon Container */
.icon-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; /* Ensures icons wrap if needed */
    gap: 2rem;
    margin-top: 1rem;
    text-align: center;
    padding: 1rem;

    a,
    button {
        font-size: 2rem;
        color: white;
        padding: 0.8rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease, color 0.3s ease, background-color 0.3s ease;
        cursor: pointer;

        &:hover {
            transform: scale(1.2);
        }
    }

    button {
        background-color: #f44336;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        border: none;
        font-weight: bold;
        transition: background-color 0.3s ease-in-out;

        &.logout-btn:hover {
            background-color: #d32f2f;
        }
    }
}

/* 🌈 Custom Background */
/* .custom-bg {
    background: radial-gradient(circle, rgba(169, 169, 169, 1) 0%, rgba(245, 222, 179, 1) 100%);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
} */

/* 📱 Responsive Design */
@media (max-width: 768px) {
    #header h1 {
        font-size: 1.8rem;
    }

    .icon-container {
        gap: 1rem;

        a,
        button {
            font-size: 1.6rem;
        }
    }
}

/* ✨ Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes iconPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}
    </style>
</head>

<body class="custom-bg">

   <!-- Header Section -->
   <header id="header"
    class="backdrop-blur-lg bg-gradient-to-r from-blue-500/30 via-purple-500/30 to-pink-500/30 border-b border-white/20 py-5 shadow-xl">
    <div class="container mx-auto flex items-center justify-between px-6">
        <!-- Title -->
        <h1 class="text-4xl font-extrabold tracking-wide text-white drop-shadow-[0_3px_10px_rgba(255,255,255,0.8)]">
           links for you
        </h1>

        <!-- Icon Container -->
        <div class="flex space-x-6" id="nav-links"></div>
    </div>
</header>


    <!-- SweetAlert2 Logout Confirmation Script -->
    <script>
        const studentName = "<?php echo htmlspecialchars(STUDENT_NAME, ENT_QUOTES, 'UTF-8'); ?>";
        const phoneNumber = "<?php echo htmlspecialchars(PHONE_NUMBER, ENT_QUOTES, 'UTF-8'); ?>";
        const studentEmail = "<?php echo htmlspecialchars(EMAIL, ENT_QUOTES, 'UTF-8'); ?>";
    const BASE_URL = ""; // Adjust if needed

const navItems = [
    { href: "index-signed.php", title: "Home", icon: "fa-home", hoverColor: "text-blue-400" },
    { href: "coursesFiles.php", title: "Files", icon: "fa-flask", hoverColor: "text-green-400" },
    { href: "save_lectures.php", title: "Lectures", icon: "fa-chalkboard-teacher", hoverColor: "text-yellow-400" },
    { href: "links.php", title: "Links", icon: "fa-link", hoverColor: "text-pink-400" },
    { href: "get-videos.php", title: "Saved Videos", icon: "fa-video", hoverColor: "text-purple-400" },
    { href: "getExams.php", title: "Exams", icon: "fa-school", hoverColor: "text-cyan-400" },
    { href: "doctors.php", title: "Doctors", icon: "fa-chalkboard-teacher", hoverColor: "text-orange-400" },
];

const navContainer = document.getElementById("nav-links");


// Ensure `navContainer` exists
if (navContainer) {
    navItems.forEach(item => {
        const link = document.createElement("a");
        link.href = studentName ? `${BASE_URL}${item.href}?name=${encodeURIComponent(studentName)}&phone=${encodeURIComponent(phoneNumber)}&email=${encodeURIComponent(studentEmail)}}` : `${BASE_URL}${item.href}`;
        link.target = "_blank";
        link.title = item.title;
        link.className = "group flex flex-col items-center transition-all transform hover:scale-125";

        const icon = document.createElement("i");
        icon.className = `fas ${item.icon} text-3xl text-white drop-shadow-md transition-all`;
        link.appendChild(icon);

        const text = document.createElement("p");
        text.className = "text-sm mt-1 text-white/80 transition-all";
        text.innerText = `${item.title}`; // Show student name in links
        link.appendChild(text);

        link.addEventListener("mouseenter", () => {
            icon.classList.add(item.hoverColor);
            text.classList.add("text-white");
        });
        link.addEventListener("mouseleave", () => {
            icon.classList.remove(item.hoverColor);
            text.classList.remove("text-white");
        });

        navContainer.appendChild(link);
    });
}


    // Logout Button
    const logoutButton = document.createElement("button");
    logoutButton.id = "logoutButton";
    logoutButton.className = "group flex flex-col items-center text-red-400 hover:text-red-600 transition-all transform hover:scale-125";
    logoutButton.innerHTML = `
        <i class="fas fa-sign-out-alt text-3xl drop-shadow-md group-hover:text-red-500 
            group-hover:animate-pulse group-hover:drop-shadow-[0_0_10px_rgba(239,68,68,0.7)]"></i>
        <p class="text-sm mt-1 text-white/80 group-hover:text-white">Logout</p>
    `;
    navContainer.appendChild(logoutButton);

    // Logout Button Confirmation
    logoutButton.addEventListener("click", function () {
        if (this.getAttribute("disabled")) return; // Prevent multiple clicks
        this.setAttribute("disabled", true); // Disable button

        executeLogout(); // ✅ Call executeLogout()
    });


// ✅ Logout Function
function executeLogout() {
    Swal.fire({
        title: "Logging Out...",
        text: "Please wait...",
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(window.location.href, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action: "logout" })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.body.style.transition = "opacity 1s ease-in-out";
            document.body.style.opacity = "0.2";

            setTimeout(() => {
                window.location.replace("welcome.php");
            }, 2000);
        } else {
            Swal.fire({
                title: "Error!",
                text: "Could not remove from database!",
                icon: "error",
            });
        }
    })
    .catch(error => console.error("Error:", error));
}

    </script>

</body>

</html>
