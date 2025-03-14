<?php
require "teacherHeader.php";

$name = $email = "";
$errorMessage = "";
$params = "name=$name&email=$email";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Dashboard</title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- D3.js -->
    <script src="https://d3js.org/d3.v7.min.js"></script>

    <!-- Tippy.js for Tooltips -->
    <script src="https://unpkg.com/@tippyjs/core@6.3.1/dist/tippy-bundle.iife.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Include GSAP & ScrollTrigger -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f1f2f6;
    color: #2c3e50;
    line-height: 1.6;
    height: 100vh;
    display: flex;
    flex-direction: column;
}


/* Parent container */
.content-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;

    /* PHP Content Box */
    .php-content {
        position: relative;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        max-width: 250px;
        text-align: center;
        animation: fadeInSlide 0.6s ease-in-out;
        overflow: auto;

        h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #16a34a; /* Green */
            margin-bottom: 8px;
        }

        p {
            font-size: 1rem;
            color: #374151; /* Gray */
            margin-top: 5px;

            span {
                color: #2563eb; /* Blue */
                font-weight: 600;
            }
        }
    }

    /* Circle Image */
    .teacher-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid #16a34a;
        object-fit: cover;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;

        &:hover {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(22, 163, 74, 0.5);
        }
    }
}

/* Icons Container */
#icons-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;

    /* Nested inside Icons */
    .icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #16a34a;
        color: white;
        font-size: 1.2rem;
        transition: transform 0.3s ease-in-out, background 0.3s ease-in-out;

        &:hover {
            transform: scale(1.1);
            background: #0f9d58;
        }
    }
}

/* Smooth Fade In Animation */
@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


        #links-icons {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            text-align: center;

            a {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                font-size: 16px;
                font-weight: 600;
                color: #fff;
                padding: 15px;
                border-radius: 12px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                transition: transform 0.3s ease;

                i {
                    font-size: 2rem;
                    transition: color 0.3s, transform 0.3s;
                }
                &:nth-child(1):hover {
            background: #ff5733;
            color: #fff;
        }

        &:nth-child(2):hover {
            background: #33c4ff;
            color: #fff;
        }

        &:nth-child(3):hover {
            background: #ff33c4;
            color: #fff;
        }

        &:nth-child(4):hover {
            background: #ffcc33;
            color: #fff;
        }

        &:nth-child(5):hover {
            background: #85e085;
            color: #fff;
        }
                .icon-text {
                    font-size: 0.9rem;
                    margin-top: 5px;
                }

                &:hover {
                    transform: scale(1.3);

                    i {
                        color: #007bff; /* Change icon color on hover */
                    }
                }
            }
        }
    

    .container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column; /* Centers content in both directions */
    width: 100%;
    max-width: 1400px;
    margin: 50px auto;
    padding: 40px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    text-align: center;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;

    &:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .img-container {
        position: relative;
        max-width: 450px;
        text-align: center;
        padding: 20px;
        background: #f8fafc;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;

        &:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
        }

        figure {
            display: flex;
            flex-direction: column;
            align-items: center;

            img {
                width: 100%;
                height: auto;
                border-radius: 12px;
                object-fit: cover;
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);

                &:hover {
                    transform: scale(1.08);
                    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
                }
            }

            figcaption {
                margin-top: 15px;
                font-size: 1.3rem;
                font-weight: bold;
                color: #1e293b; /* Dark gray */
                text-transform: uppercase;
                letter-spacing: 1.5px;
                transition: color 0.3s ease-in-out;

                &:hover {
                    color: #16a34a; /* Green accent */
                }

                &::before {
                    content: "📷 ";
                }

                &::after {
                    content: " 🌟";
                }
            }
        }
    }
}

/* Animation */
@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}


#footer {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #2c3e50;
    color: white;
    padding: 40px 20px;
    width: 100%;
    text-align: center;
    border-top: 5px solid #f39c12;
    box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);

    p {
        font-size: 18px;
        margin-bottom: 10px;
        opacity: 0.8;
    }

    .footer-links {
        display: flex;
        gap: 30px;
        margin-top: 10px;

        a {
            color: white;
            font-size: 18px;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease, transform 0.2s ease;

            &:hover {
                color: #f39c12;
                transform: translateY(-2px);
            }
        }
    }

    .social-icons {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;

        .social-icon {
            font-size: 2.5rem;
            text-decoration: none;
            transition: transform 0.3s ease, color 0.3s ease;
            
            &:hover {
                transform: scale(1.2);
            }

            &.facebook {
                color: #1877F2;
                &:hover {
                    color: #125abe;
                }
            }

            &.twitter {
                color: #1DA1F2;
                &:hover {
                    color: #0c85d0;
                }
            }

            &.email {
        color: #ea4335; /* Gmail-like color */
        
        &:hover {
            transform: scale(1.3);
            color: #d32f2f; /* Darker red on hover */
        }
    }

            &.linkedin {
                color: #0A66C2;
                &:hover {
                    color: #084a8d;
                }
            }

            &.youtube {
                color: #FF0000;
                &:hover {
                    color: #cc0000;
                }
            }
        }
    }

    .icon-text {
        font-size: 16px;
        margin-top: 10px;
        opacity: 0.7;
    }

    /* Footer animation */
    @media (max-width: 768px) {
        flex-direction: column;
        text-align: center;
        padding: 30px 10px;

        .footer-links {
            flex-direction: column;
            gap: 15px;
        }

        .social-icons {
            flex-wrap: wrap;
            gap: 15px;
        }
    }
}


    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out;
    }
   

/* Fade-in and slide-down effect */
@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive design */
@media (max-width: 600px) {
    .php-content {
        left: 5px;
        top: 5px;
        width: 95%;
        padding: 15px;
    }
}

    </style>
</head>

<body>
<div id="icons-container">
<?php 
       $name = isset($_GET['name']) ? rawurlencode($_GET['name']) : 'unknown';
       $email = isset($_GET['email']) ? rawurlencode($_GET['email']) : 'no email';
       $phone = isset($_GET['phone']) ? rawurlencode($_GET['phone']) : 'no phone';
       $subject = isset($_GET['subject']) ? rawurlencode($_GET['subject']) : 'no subject';
       $year = isset($_GET['year']) ? rawurlencode($_GET['year']) : 'no year';
    ?>

    <div class="fixed top-0 left-0 p-4 z-50 flex items-center gap-4">
        <!-- Profile Image (Click to Show Dashboard) -->
        <img src="<?= $image; ?>" alt="Profile" class="w-12 h-12 rounded-full border-4 border-green-500 cursor-pointer transition-transform transform hover:scale-110" onclick="toggleDashboard()">

        <!-- Dashboard (Initially Hidden) -->
        <div id="dashboard" class="hidden bg-white p-4 shadow-lg rounded-lg w-72 border border-gray-200">
            <h2 class="text-gray-700 mt-2">👤 name:<span class="text-blue-500"><?= $name; ?></span></h2>
            <p class="text-gray-700 mt-2">📧 Email: <span class="text-blue-500"><?= $email; ?></span></p>
            <p class="text-gray-700 mt-2">📞 Phone: <span class="text-blue-500"><?= $phone; ?></span></p>
            <p class="text-gray-700 mt-2">📞 year: <span class="text-blue-500"><?= $year; ?></span></p>
            <p class="text-gray-700 mt-2">📞 subject: <span class="text-blue-500"><?= $subject; ?></span></p>
            <button onclick="toggleDashboard()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Close Dashboard
            </button>
        </div>
    </div>

<div id="links-icons">
    <a href="add_course.php?<?php echo $params; ?>" target="_blank" title="Add Files">
        <i class="fas fa-plus-circle"></i>
        <div class="icon-text">Add Files</div>
    </a>

    <a href="coursesDescription.php?<?php echo $params; ?>" target="_blank" title="Add Courses">
    <i class="fas fa-book-open"></i>
<div class="icon-text">Add Courses</div>

    </a>

    <a href="lectures.php?<?php echo $params; ?>" target="_blank" title="Add Lecture">
        <i class="fas fa-chalkboard-teacher"></i>
        <div class="icon-text">Add Lectures</div>
    </a>

    <a href="exams.php?<?php echo $params; ?>" target="_blank" title="Add Exam">
    <i class="fas fa-pencil-alt"></i>
<div class="icon-text">Add Exams</div>

    </a>

    <a href="enrolled.php" target="_blank" title="Available Students">
    <i class="fas fa-users"></i>
<div class="icon-text">Available Students</div>

    </a>
</div>

    <div class="container">
        <div class="img-container">
            <figure>
                <img src="science.jpg" alt="science">
                <figcaption>Faculty of science</figcaption>
            </figure>
        </div>
    </div>

</div>

    <!-- Footer Section -->
    <footer id="footer">
    <p>© 2025 Your Website. All Rights Reserved.</p>
    
    <div class="footer-links">
        <a href="#">facebook</a>
        <a href="#">linkedin</a>
        <a href="#">email</a>
        <a href="#">youtube</a>
    </div>

    <div class="social-icons">
        <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
        <a href="mailto:info@zu.edu.eg" class="social-icon email">
        <i class="fas fa-envelope"></i>
        </a>
        <a href="#" class="social-icon youtube">
    <i class="fab fa-youtube"></i>
</a>

        </div>
    <p class="icon-text">Follow us on social media</p>
</footer>



    <!-- Scripts -->
    <script>
        function toggleDashboard() {
            document.getElementById('dashboard').classList.toggle('hidden');
        }
        // Tippy.js: Enable tooltips for icons
        tippy('#image-icons a', {
            content: (reference) => reference.getAttribute('title'),
            theme: 'light',
            animation: 'scale',
            delay: [5, 0],
            arrow: true
        });

        // SweetAlert logout confirmation
       

gsap.from("#gradient-section h1", {
        opacity: 0,
        y: -50,
        rotationX: 90, // Rotates as it appears
        duration: 2,
        ease: "power3.out",
        delay: 0.5
    });

    gsap.from("#gradient-section p", {
        opacity: 0,
        x: -50,
        duration: 1.5,
        ease: "power2.out",
        delay: 1
    });

    gsap.from("#button-container a", {
        opacity: 0,
        scale: 0.5,
        y: 20,
        duration: 1.2,
        stagger: 0.2, // Staggers buttons one after the other
        ease: "back.out(1.7)", 
        delay: 1.5
    });

    // Parallax Background Effect
    gsap.to("#gradient-section", {
        backgroundPosition: "50% 100%",
        scrollTrigger: {
            trigger: "#gradient-section",
            start: "top center",
            scrub: true
        }
    });

    // Floating Effect for Icons
    gsap.to("#button-container a", {
        y: 10,
        repeat: -1,
        yoyo: true,
        duration: 1.5,
        ease: "sine.inOut"
    });
    </script>
</body>

</html>
