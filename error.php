<?php
// error.php
// Displays an error message when something goes wrong

header("Content-Type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Zagzig University</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Simple Error Page Styling */
        body {
            font-family: 'Roboto', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            color: #333;
            text-align: center;
        }

        .error-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .error-title {
            font-size: 24px;
            font-weight: bold;
            color: #ff4e4e;
        }

        .error-message {
            font-size: 18px;
            margin: 20px 0;
        }

        .retry-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .retry-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-6 md:p-8 rounded-lg shadow-lg max-w-md text-center border border-red-300 animate-fade-in">
        <!-- Error Icon -->
        <div class="text-red-500 text-6xl mb-4 animate-bounce">
            ⚠️
        </div>

        <!-- Error Title -->
        <h2 class="text-2xl font-bold text-red-600 mb-2">Oops! Something Went Wrong</h2>

        <!-- Error Message -->
        <p class="text-gray-700 mb-6">
            We're unable to load the page due to a connection issue.<br> 
            Please check your internet connection and try again.
        </p>

        <!-- Retry Button -->
        <button onclick="window.location.reload();"
            class="px-6 py-2 bg-red-500 text-white rounded-lg shadow-md font-semibold hover:bg-red-600 transition-all duration-300 transform hover:scale-105">
            🔄 Retry
        </button>
    </div>
</div>


    <script>
        // SweetAlert2 error popup for better user experience
        Swal.fire({
            title: 'Connection Error',
            text: 'We could not detect an internet connection. Please check your connection and try again.',
            icon: 'error',
            confirmButtonText: 'Reload Page',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    </script>
</body>

</html>
