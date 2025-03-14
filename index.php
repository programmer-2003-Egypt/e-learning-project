<?php
// Database connection
$host = 'localhost';
$user = 'root';  // Change if using a different MySQL user
$pass = '';      // Add your MySQL password if applicable
$dbname = 'project';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all specializations
$sql = "SELECT * FROM specializations ORDER BY name ASC";
$result = $conn->query($sql);
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

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.0/gsap.min.js"></script>

    <!-- Swiper (Slider) -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Tippy.js for hover tooltips -->
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy.umd.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
     body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #00C2A4, #0077B5);
    color: #fff;
    transition: all 0.3s ease-in-out;

    nav {
        background: rgba(0, 119, 181, 0.9);
        position: sticky;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        padding: 10px 0;
        transition: background 0.3s ease-in-out;

        ul {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;

            li {
                list-style: none;

                .nav-link {
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    font-size: 16px;
                    display: flex;
                    align-items: center;
                    transition: background-color 0.3s ease-in-out;

                    i {
                        margin-right: 8px;
                    }

                    &:hover {
                        background-color: rgba(255, 255, 255, 0.1);
                    }
                }

                &.active {
                    background-color: rgba(0, 194, 164, 0.6);
                }
            }
        }
    }

    .sign-btn {
        position: absolute;
        top: 10px;
        right: 20px;
        background-color: #00C2A4;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;

        &:hover {
            background-color: #0077B5;
        }

        &#signin-teacher {
            background-color: #00C2A4;

            &:hover {
                background-color: #0077B5;
            }
        }

        &#signin-student {
            background-color: blue;
            right: 250px;

            &:hover {
                background-color: darkblue;
            }
        }
    }

    .content-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 30px;

        .content {
            flex: 1;
            padding-right: 30px;
        }

        .image-container {
            flex: 0 0 40%;
            max-width: 40%;

            img {
                width: 100%;
                height: 50%;
                border-radius: 10px;

                &:hover {
                    border: 3px solid black;
                    transform: scale(1.2);
                }
            }
        }
    }

    header {
        h1, p {
            opacity: 0;
            animation: fadeIn 1.5s ease-in-out forwards;
        }
    }

    .card {
        background-color: #ffffff;
        color: #333;
        margin: 15px;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;

        &:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }
    }

    .footer {
        background: rgba(0, 119, 181, 0.9);
        color: #fff;
        text-align: center;
        padding: 30px;
        width: 100%;

        i {
            font-size: 30px;
            margin: 0 10px;
            cursor: pointer;

            &:hover {
                background: blue;
            }
        }
    }

    .grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }

    .quote {
        font-style: italic;
        font-size: 1.5rem;
        margin-top: 30px;
    }

    .swiper-container {
        margin-left: 10px;
        width: 30%;
        height: 200px;

        .swiper-slide {
            background-size: cover;
            background-position: center;
            height: 100%;
        }
    }

    .chart-container {
        width: 100%;
        height: 400px;
    }
}

// Animations
@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

    </style>
</head>
<body>

<nav class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg fixed w-full top-0 left-0 z-50">
    <ul class="flex justify-center space-x-6 py-4 text-white font-semibold text-lg">
        <li>
            <a href="#home" class="nav-link group" id="home-link">
                <i class="fas fa-home"></i>
                <span>Home</span>
                <span class="nav-underline"></span>
            </a>
        </li>
        <li>
            <a href="#about" class="nav-link group" id="about-link">
                <i class="fas fa-info-circle"></i>
                <span>About</span>
                <span class="nav-underline"></span>
            </a>
        </li>
        <li>
            <a href="#research" class="nav-link group" id="research-link">
                <i class="fas fa-flask"></i>
                <span>Research</span>
                <span class="nav-underline"></span>
            </a>
        </li>
        <li>
            <a href="#courses" class="nav-link group" id="courses-link">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Courses</span>
                <span class="nav-underline"></span>
            </a>
        </li>
        <li>
            <a href="#performance" class="nav-link group" id="performance-link">
                <i class="fas fa-chart-line"></i>
                <span>Performance</span>
                <span class="nav-underline"></span>
            </a>
        </li>
    </ul>
    <!-- Sign In Button -->
    <button id="signin-btn" class="sign-btn">Sign In</button>
</nav>


    <!-- Content & Image Wrapper -->
    <div class="content-wrapper" id="content-wrapper">
        <div class="content" id="content"></div>
        <!-- Image -->
        <div class="image-container">
            <img src="zagazig.png" alt="University Image">
        </div>
    </div>

    <!-- Footer with Social Media Links -->
    <div class="footer">
        <p>© 2024 Zagazig University. All rights reserved.</p>
        <div>
            <i class="fab fa-facebook"></i>
            <i class="fab fa-twitter"></i>
            <i class="fab fa-instagram"></i>
            <i class="fab fa-linkedin"></i>
            <i class="fas fa-envelope"></i>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.all.min.js"></script>


    <!-- JavaScript (Dynamic Content & OOP) -->
    <script>
    document.getElementById('signin-btn').addEventListener('click', async () => {
    try {
        // 🌟 Step 1: Select Role
        const { value: role } = await Swal.fire({
            title: '🔰 Select Your Role',
            input: 'radio',
            inputOptions: {
                'admin': '🛠️ <b>Admin</b>',
                'teacher': '👨‍🏫 <b>Teacher</b>',
                'student': '🎓 <b>Student</b>'
            },
            inputValidator: (value) => {
                if (!value) return '⚠ Please select a role!';
            },
            confirmButtonText: 'Next ⏭',
            showCancelButton: true,
            cancelButtonText: 'Cancel ❌',
            allowOutsideClick: false,
            background: '#1E293B',
            color: '#F8FAFC',
            customClass: {
                popup: 'rounded-lg shadow-2xl',
                confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full',
                cancelButton: 'bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-full',
            }
        });

        if (!role) return; // Exit if canceled

        // 🌟 Step 2: Redirect to Login Page (NO iFrame)
        window.location.href = `login_${role}.php`;

    } catch (error) {
        console.error("Error during login process:", error);
        Swal.fire({
            title: '❌ Error',
            text: 'Something went wrong!',
            icon: 'error',
            background: '#1E293B',
            color: '#F8FAFC',
            allowOutsideClick: false
        });
    }
});

        // Continue with your existing SPA code for handling navigation links
        const links = document.querySelectorAll('.nav-link');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                   // Remove the active class from all links
        links.forEach(link => link.classList.remove('active'));
        // Add the active class to the clicked link
        link.classList.add('active');
                loadContent(e.target.getAttribute('href').substring(1));
            });
        });

        function loadContent(section) {
    const content = document.getElementById('content');

    const sections = {
        home: `
            <header class="text-center py-20 bg-gradient-to-r from-teal-500 to-green-400 text-white rounded-lg shadow-lg">
                <h1 class="text-5xl font-bold">Welcome to Zagazig University</h1>
                <p class="text-2xl mt-4">Innovating the Future of Education</p>
            </header>
        `,
        about: `
           <div class="card bg-white shadow-lg p-6 rounded-lg border border-gray-200">
    <h2 class="text-3xl font-semibold text-gray-800 mb-4">About Zagazig University</h2>
    <p class="text-lg text-gray-600 leading-relaxed mb-4">
        Founded in 1974, Zagazig University is a public institution located in Zagazig, Egypt. :contentReference[oaicite:0]{index=0} It is one of the largest governmental universities in Egypt, with approximately 7,000 faculty members and over 120,000 students enrolled across 24 academic colleges and institutions. :contentReference[oaicite:1]{index=1} The university offers a diverse range of undergraduate and postgraduate programs, contributing significantly to the nation's educational and research advancements. :contentReference[oaicite:2]{index=2}
    </p>
    <p class="text-lg text-gray-600 leading-relaxed mb-4">
        Zagazig University is committed to excellence in education, scientific research, and community development. It has been recognized in various global university rankings, reflecting its dedication to maintaining high academic standards. :contentReference[oaicite:3]{index=3}
    </p>
    <p class="text-lg text-gray-600 leading-relaxed">
        The university's vision is to become an accredited and internationally recognized institution, known for its distinguished level of education, scientific research, and sustainable community development. :contentReference[oaicite:4]{index=4}
    </p>
</div>

        `,
        research: `
            <div class="card bg-white shadow-lg p-6 rounded-lg border border-gray-200">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">Research at Zagazig</h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    We focus on cutting-edge research in various fields, including science, technology, and healthcare.
                </p>
            </div>
        `,
        courses: `
 <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg max-w-3xl">
        <h1 class="text-3xl font-bold text-center mb-6"> Specializations</h1>

        <?php if ($result->num_rows > 0): ?>
            <ul class="space-y-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="p-5 bg-gray-200 rounded-lg shadow">
                        <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p class="text-gray-700"><?php echo htmlspecialchars($row['description']); ?></p>

                        <!-- Process subjects -->
                        <?php 
                        $subjects = array_filter(array_map('trim', explode(',', $row['subjects']))); 
                        $subject_count = count($subjects);
                        ?>

                        <p class="mt-2 text-gray-800 font-bold">📚 Subjects Count: <?php echo $subject_count; ?></p>

                        <?php if ($subject_count > 0): ?>
                            <ul class="mt-3 space-y-2">
                                <?php foreach ($subjects as $subject): ?>
                                    <li class="ml-5 text-gray-700">🔹 <?php echo htmlspecialchars($subject); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="ml-5 text-gray-500 italic">No subjects available.</p>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-center text-gray-600">No specializations found.</p>
        <?php endif; ?>
    </div>

    
        `,
        performance: `
            <div class="card bg-white shadow-lg p-6 rounded-lg border border-gray-200">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4">University Performance</h2>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Our university ranks among the top institutions for quality education and research in the region.
                </p>
                <div class="chart-container mt-6 p-4 bg-gray-100 rounded-lg">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        `
    };

    content.innerHTML = sections[section] || `
        <div class="text-center p-10 text-gray-700 text-2xl font-semibold">
            Section not found.
        </div>
    `;

    if (section === 'performance') {
        setTimeout(() => {
            const ctx = document.getElementById('performanceChart')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['2020', '2021', '2022', '2023'],
                        datasets: [{
                            label: 'University Performance',
                            data: [80, 85, 90, 95],
                            backgroundColor: 'rgba(0, 194, 164, 0.6)',
                            borderColor: 'rgba(0, 194, 164, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }, 0);
    }
}

    </script>

</body>
</html>
