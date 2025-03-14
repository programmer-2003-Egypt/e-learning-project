<?php
session_start();

// Check if the user is signed in
$isSignedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zagazig University - Advanced</title>

    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00C2A4, #0077B5);
            color: #fff;
        }
        nav {
            background: rgba(0, 119, 181, 0.9);
            position: sticky;
            top: 0;
            width: 100%;
            padding: 10px 0;
            z-index: 1000;
        }
        ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 16px;
            transition: background 0.3s;
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .back-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #0077B5;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: <?= $isSignedIn ? 'none' : 'block' ?>;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="#home" class="nav-link"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="#about" class="nav-link"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="#research" class="nav-link"><i class="fas fa-flask"></i> Research</a></li>
            <li><a href="#courses" class="nav-link"><i class="fas fa-chalkboard-teacher"></i> Courses</a></li>
            <li><a href="#performance" class="nav-link"><i class="fas fa-chart-line"></i> Performance</a></li>
        </ul>
    </nav>

    <!-- Back to Page Button -->
    <button class="back-button" onclick="history.back()">Back to Page</button>

    <!-- Content -->
    <div class="content">
        <h1>Welcome to Zagazig University</h1>
        <p>Innovating the Future of Education</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2024 Zagazig University. All rights reserved.</p>
    </div>

</body>
</html>
