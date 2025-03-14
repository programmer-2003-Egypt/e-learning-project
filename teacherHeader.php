<?php
// Database credentials
$host = "localhost";
$dbname = "project";
$username = "root"; // Change this if needed
$password = ""; // Change this if needed

try {
    // Create a new PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['name'])) {
        $name = $_GET['name'];

        // Prepare and execute the DELETE statement
        $stmt = $conn->prepare("DELETE FROM doctors WHERE name = :name");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "✅ Doctor '$name' has been removed successfully!";
        } else {
            $message = "⚠️ No doctor found with the name '$name'.";
        }
    }
} catch (PDOException $e) {
    $message = "❌ Database Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS file -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
  /* Gradient Section with Advanced Effects */
#gradient-section {
    background: linear-gradient(135deg, rgb(102, 100, 138), rgb(22, 15, 28), rgb(32, 5, 19));
    color: white;
    text-align: center;
    padding: 100px 50px;
    border-radius: 30px;
    position: relative;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
    overflow: auto;
    transform: perspective(1000px) rotateX(5deg);
    transition: transform 0.3s ease-in-out;

    &:hover {
        transform: perspective(1000px) rotateX(0deg);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
    }

    /* Overlay */
    .overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 30px;
        z-index: 1;
    }

    /* Heading */
    h1 {
        position: relative;
        font-size: 48px;
        font-weight: 800;
        letter-spacing: 2.5px;
        text-shadow: 0 5px 12px rgba(0, 0, 0, 0.4);
        opacity: 0;
        animation: fadeInUp 1s ease-in-out forwards;
        background: linear-gradient(90deg, #ff8c00, #ff0055);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Subheading */
    p {
        position: relative;
        font-size: 22px;
        font-weight: 300;
        margin-top: 20px;
        opacity: 0;
        animation: fadeInUp 1s ease-in-out forwards 0.3s;
    }

    /* Buttons */
    #button-container {
        position: relative;
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 50px;
        z-index: 2;

        a {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 18px 35px;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            border-radius: 18px;
            text-decoration: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 3px solid transparent;
            background-size: 300% 300%;

            i {
                font-size: 26px;
                transition: transform 0.3s ease;
            }
        }

        /* Home Button */
        #home-button {
            background: linear-gradient(45deg, #3b82f6, #60a5fa, #93c5fd);
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.4);
            transition: 0.4s;
            animation: gradientShift 3s infinite linear;

            &:hover {
                background: linear-gradient(45deg, #2563eb, #1e40af);
                color: white;
                transform: scale(1.15);
                border-color: white;
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
                animation: none;

                i {
                    transform: rotate(15deg);
                }
            }
        }

        /* Logout Button */
        #logout-button {
            background: linear-gradient(45deg, #f87171, #ef4444, #dc2626);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
            transition: 0.4s;
            animation: gradientShift 3s infinite linear;

            &:hover {
                background: linear-gradient(45deg, #b91c1c, #991b1b);
                color: white;
                transform: scale(1.15);
                border-color: white;
                box-shadow: 0 0 15px rgba(239, 68, 68, 0.8);
                animation: none;

                i {
                    transform: rotate(-15deg);
                }
            }
        }
    }
}

/* Floating Top Right Image */
#top-right-image {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 140px;
    height: auto;
    border-radius: 5px;
    opacity: 0.9;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease-in-out;

    &:hover {
        transform: scale(1.1) rotate(5deg);
    }
}

/* Keyframe Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}
</style>
</head>
<body>

<!-- Gradient Section -->
<section id="gradient-section">
    <div class="overlay"></div>
    <h1>Hello to Zagazig University</h1>
    <p>Faculty of Science</p>

    <!-- Buttons Container -->
    <div id="button-container">
        <a id="home-button" href="index-signed.php" target="_blank">
            <i class="fas fa-home"></i> Home
        </a>
        <a id="logout-button" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</section>

<!-- Top-Right Image -->
<img id="top-right-image" src="zagazig.png" alt="Zagazig University Logo">
<script>
     function confirmLogout() {
    Swal.fire({
        title: '⚠️ Confirm Logout',
        text: 'Are you sure you want to log out?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, log me out!',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'bg-gray-900 text-white shadow-xl rounded-lg',
            title: 'text-2xl font-bold',
            confirmButton: 'bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md transition-all',
            cancelButton: 'bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded-md transition-all',
        },
        backdrop: `
            rgba(0, 0, 0, 0.7)
          
            center left
            no-repeat
        `,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            setTimeout(() => {
                window.location.replace('welcome.php'); // Redirect after confirmation
            }, 1200); // Delay for sound effect
        }
    });
}
</script>
<?php if (isset($message)) : ?>
    <script>
        Swal.fire({
            title: "Info",
            text: "<?= $message; ?>",
            icon: "info",
            confirmButtonText: "OK"
        });
    </script>
<?php endif; ?>
</body>
</html>
