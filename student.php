<?php
define("STUDENT_NAME", isset($_GET["name"]) ? $_GET["name"] : "Unknown");
define("EMAIL", isset($_GET["email"]) ? $_GET["email"] : "Unknown");
define("PHONE_NUMBER", isset($_GET["phone"]) ? $_GET["phone"] : "Unknown");
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : "Guest";
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : "No Email";
$phone = isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : "No phone";
$profile_image = isset($_GET['img']) ? htmlspecialchars($_GET['img']) : "person.png";
require "studentHeader.php";
require "connect.php";

// Sanitize and validate user input for pagination, sorting, and search
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$sort = isset($_GET['sort']) && in_array($_GET['sort'], ['title', 'price', 'department']) ? $_GET['sort'] : 'title';
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';

// Pagination setup
$itemsPerPage = 6;
$offset = ($page - 1) * $itemsPerPage;

// Prepare SQL query with parameterized statements to prevent SQL injection
$sql = "SELECT * FROM dynamiccourses WHERE title LIKE ? ORDER BY $sort LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%"; // Search term with wildcards for LIKE
$stmt->bind_param('sii', $searchTerm, $itemsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total courses for pagination
$totalCoursesQuery = "SELECT COUNT(*) AS total FROM dynamiccourses WHERE title LIKE ?";
$totalCoursesStmt = $conn->prepare($totalCoursesQuery);
$totalCoursesStmt->bind_param('s', $searchTerm);
$totalCoursesStmt->execute();
$totalCoursesResult = $totalCoursesStmt->get_result();
$totalCourses = $totalCoursesResult->fetch_assoc()['total'];
$totalPages = ceil($totalCourses / $itemsPerPage);
// Count Physics (PHY) courses
$phyCoursesQuery = "SELECT COUNT(*) AS phy_total FROM dynamiccourses WHERE department = 'Physics' AND title LIKE ?";
$phyCoursesStmt = $conn->prepare($phyCoursesQuery);
$phyCoursesStmt->bind_param('s', $searchTerm);
$phyCoursesStmt->execute();
$phyCoursesResult = $phyCoursesStmt->get_result();
$phyCourses = $phyCoursesResult->fetch_assoc()['phy_total'];

// Count Computer Science (CS) courses
$csCoursesQuery = "SELECT COUNT(*) AS cs_total FROM dynamiccourses WHERE department = 'Computer Science' AND title LIKE ?";
$csCoursesStmt = $conn->prepare($csCoursesQuery);
$csCoursesStmt->bind_param('s', $searchTerm);
$csCoursesStmt->execute();
$csCoursesResult = $csCoursesStmt->get_result();
$csCourses = $csCoursesResult->fetch_assoc()['cs_total'];

$totalPages = ceil($totalCourses / $itemsPerPage);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
    <!-- Materialize CSS Framework -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>
    <style>
      body {
    background: linear-gradient(135deg, #f06d7b, #f7b8b8);
    animation: gradientChange 60s ease-in-out infinite;
    font-family: 'Roboto', sans-serif;
}

@keyframes gradientChange {
    0% { background: linear-gradient(135deg, #000000,rgb(70, 107, 126)); } /* Deep Black to Soft Cyan */
    25% { background: linear-gradient(135deg, #001f3f,rgb(5, 66, 78)); } /* Dark Blueish Black to Vibrant Cyan */
    50% { background: linear-gradient(135deg, #004466, #b0c4de); } /* Deep Cyan to Light Steel Blue */
    75% { background: linear-gradient(135deg, #5f7d8a, #c0c0c0); } /* Muted Cyan to Silver Grey */
    100% { background: linear-gradient(135deg, #808080, #1a1a1a); } /* Soft Grey to Charcoal Black */


}

.course-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    height: fit-content;
    cursor: pointer;
    border-radius: 10px;
    width:fit-content;
    overflow:auto;

    &:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card-image {
        img {
           
            object-fit: cover;
            border-radius: 5px;
        }
    }

    .card-content {
        flex-grow: 1;
    }
}

.swiper-container {
    margin-top: 20px;

    .swiper-slide {
        img {
            width: 100%;
            border-radius: 5px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }
    }
}

/* Swiper Navigation Buttons */
.swiper-button {
    &-next,
    &-prev {
        background-color: rgba(0, 0, 0, 0.6);
        padding: 12px;
        border-radius: 50%;
        color: white;
        width: 45px;
        height: 45px;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease, transform 0.2s ease;

        &:hover {
            background-color: rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        &:active {
            transform: scale(0.9);
        }
    }
}

/* Swiper Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding: 10px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);

    .pagination-info {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .pagination-button-container {
        display: flex;
        gap: 12px;

        .pagination-button {
            padding: 8px 16px;
            border-radius: 8px;
            background: linear-gradient(135deg, #4A90E2, #007BFF);
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            transition: background 0.3s, transform 0.2s ease;
            cursor: pointer;

            &:hover {
                background: linear-gradient(135deg, #007BFF, #4A90E2);
                transform: scale(1.05);
            }

            &:active {
                transform: scale(0.95);
            }

            &[disabled] {
                background-color: #ddd;
                color: #666;
                cursor: not-allowed;
            }
        }
    }
}

.advanced-input {
    position: relative;
    width: 400px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content:center;
    /* Input Field */
    input {
        width: 50%;
        padding: 14px 24px; // Space for icon
        border-radius: 5px;
        font-size: 16px;
        color: #333;
        cursor:pointer;
        border: 2px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(5px);
        transition: all 0.3s ease-in-out;

        &::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        &:focus {
            outline: none;
            border-color: #00bfff;
            box-shadow: 0 6px 15px rgba(0, 191, 255, 0.4);
            background: rgba(255, 255, 255, 0.4);
        }

        &:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Error State */
        &.error {
            border-color: #ff4d4d;
            box-shadow: 0 0 10px rgba(255, 77, 77, 0.5);
        }
    }

    /* Icons Inside Input */
    .input-icon {
        position: absolute;
        left: 16px;
        font-size: 20px;
        color: rgba(0, 0, 0, 0.6);
    }
}

/* Animated Label */
.animated-label {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #777;
    pointer-events: none;
    transition: 0.3s ease-in-out;

    input:focus ~ &,
    input:not(:placeholder-shown) ~ & {
        top: 8px;
        font-size: 12px;
        color: #007BFF;
    }
}

/* Error Message */
.error-message {
    font-size: 12px;
    color: #ff4d4d;
    margin-top: 4px;
    display: none;

    .error + & {
        display: block;
    }
}
.course-container {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
    width: 100%;
    text-align: center;

    /* Centering Trick */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    /* Title */
    .course-title {
        font-size: 28px;
        font-weight: bold;
        color: grey;
        margin-bottom: 20px;
    }

    /* Course Stats Row */
    /* Course Stats Container */
.course-stats {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    padding: 20px;

    /* Individual Course Box */
    .course-box {
        flex: 1;
        min-width: 200px;
        max-width: 300px;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 2px 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        background: linear-gradient(135deg,rgb(18, 65, 112),rgb(11, 8, 37));
        transition: all 0.3s ease-in-out;
        position: relative;
        overflow: hidden;
        cursor: pointer;

        /* Hover Effects */
        &:hover {
            transform: scale(1.08);
            box-shadow: 4px 6px 20px rgba(0, 0, 0, 0.15);

            .icon {
                transform: rotate(10deg) scale(1.1);
            }

            .title {
                color: #ff6600;
            }
        }

        /* Icon Styling */
        .icon {
            font-size: 50px;
            margin-bottom: 10px;
            color: #007bff;
            transition: transform 0.3s ease-in-out;
        }

        /* Number Display */
        .count {
            display: block;
            font-size: 32px;
            font-weight: bold;
            margin-top: 5px;
            color:rgb(17, 213, 99);
        }

        /* Course Title */
        .title {
            font-size: 18px;
            font-weight: 500;
            color: #555;
            margin-top: 5px;
            transition: color 0.3s ease-in-out;
        }

        /* Special Borders */
        &.total {
            border-left: 12px solid blue;
        }

        &.physics {
            border-left: 12px solid green;
        }

        &.cs {
            border-left: 12px solid red;
        }
    }
}
}

/* Responsive Design */
@media (max-width: 768px) {
    .course-stats {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .course-box {
        max-width: 80%;
        width: 100%;
    }
}

.container {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 10px;
  
    transition: transform 0.3s ease-in-out;

    &:hover {
        transform: scale(1.05);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .user-img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #16a34a;
    }

    .text-info {
    display: none; /* Hidden by default */
    margin-left: 15px;
    flex-direction: column;
}

.text-info.show {
    display: flex; /* Show when clicked */
}


    h2 {
        font-size: 20px;
        margin-bottom: 2px;
    }
    .email {
        font-style: italic;
        color: #f1c40f;
        font-weight: bold;
    }
    .name {
        font-style: italic;
        color:rgb(15, 241, 68);
        font-weight: bold;
    }

    .phone {
        color: #3498db;
        font-weight: bold;
    }
}

    </style>
</head>
<body>
<div class="container" onclick="toggleInfo()">
    <img src="<?php echo $profile_image; ?>" alt="User Image" class="user-img">
    <div class="text-info">
        <h2>name:<span class="name"><?php echo $name; ?></span> 👋</h2>
        <h2>email: <span class="email"><?php echo $email; ?></span></h2>
        <h2>phone: <span class="phone"><?php echo $phone; ?></span></h2>
    </div>
</div>
<div class="course-container">
        <h2 class="course-title">📊 about courses</h2>

        <div class="course-stats">
            <!-- Total Courses -->
            <div class="course-box total">
                <div class="icon">📚</div>
                <p>Total Courses:</p>
                <span><?= $totalCourses ?></span>
            </div>

            <!-- Physics Courses -->
            <div class="course-box physics">
            <div class="icon"><i class="fas fa-wave-square"></i></div>
                <p>Physics Courses:</p>
                <span><?= $phyCourses ?></span>
            </div>

            <!-- Computer Science Courses -->
            <div class="course-box cs">
                <div class="icon">💻</div>
                <p>Computer Science Courses:</p>
                <span><?= $csCourses ?></span>
            </div>
        </div>
    </div>

<div class="container mx-auto p-6">
    <!-- Search Bar -->
    <div class="flex justify-center">
        <form id="search-form" style="width:100%"
        class="md:w-2/3 lg:w-1/2 bg-white shadow-xl rounded-xl p-6 flex items-center gap-4 border border-gray-200 min-h-[80px] md:min-h-[100px] lg:min-h-[120px]" onsubmit="filterCourses(event)">
            
           

         
<div class="advanced-input">
    <i class="input-icon">🔍</i>
    <input id="search-bar" type="text" placeholder=" " class="search-input">
    <label class="animated-label">Search...</label>
    <span class="error-message">Invalid input</span>
</div>


<div class="advanced-input">
    <i class="input-icon">💰</i>
    <input id="price-filter" type="text" placeholder=" " class="search-input">
    <label class="animated-label">Enter price range (e.g., <=100 OR >=50 AND =30, 50-100)</label>
    <span class="error-message">Invalid price range</span>
</div>


<div class="advanced-input">
    <i class="input-icon">📅</i>
    <input id="date-filter" type="text" placeholder=" " class="search-input">
    <label class="animated-label">Enter date range (e.g., >=2024-01-01, <=2025-12-31)</label>
    <span class="error-message">Invalid date format</span>
</div>



            
            <!-- Submit Button -->
            <button type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-lg transition-all transform hover:scale-105">
                🔍
            </button>
        </form>
    </div>
</div>
 <!-- Buttons to Filter Departments -->
 <div class="flex justify-center my-6 space-x-4">
        <button onclick="searchCourses('Physics')" class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
            PHY
        </button>
        <button onclick="searchCourses('Computer Science')" class="px-6 py-3 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
            CS
        </button>
    </div>

    <!-- Courses Container -->
    <div id="courses-container" class="swiper-container">
        <div class="swiper-wrapper" id="courses-wrapper">
            <?php
            // Display course details in Swiper slides
            if ($result->num_rows > 0) {
                while ($course = $result->fetch_assoc()) {
                    $images = json_decode($course['image'], true); 
                    $courseTitle = htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8');
                    $courseDoctor = htmlspecialchars($course['doctor_name'], ENT_QUOTES, 'UTF-8');
                    $coursePrice = htmlspecialchars($course['price'], ENT_QUOTES, 'UTF-8');
                    $courseDepartment = htmlspecialchars($course['department'], ENT_QUOTES, 'UTF-8');
                    $courseDescription = htmlspecialchars($course['description'], ENT_QUOTES, 'UTF-8');
                    $courseStartDate = htmlspecialchars($course['start_date'], ENT_QUOTES, 'UTF-8');
/*                     $images = json_decode($course['course_image'], true); */

                    

$courseCard = <<<HTML
<div class="swiper-slide course-card" data-department="{$courseDepartment}">
    <div class="bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105 duration-300 flex w-full">
        
        <!-- Image -->
        <div class="w-full">
            <img src="{$images[0]}" alt="Course Image" class="w-full h-full object-cover rounded-l-lg">
        </div>

        <!-- Course Info -->
        <div class="w-full flex flex-col justify-center bg-white shadow-lg rounded-r-lg p-6 border border-gray-200">
    <h3 class="text-2xl font-bold text-gray-900 truncate mb-2 flex items-center">
        <i class="fas fa-book text-blue-500 mr-2"></i> <!-- Course Icon -->
        {$courseTitle}
    </h3>

    <div class="bg-white shadow-lg rounded-xl p-6 space-y-4 border border-gray-200">
        
        <div class="flex items-center text-lg text-gray-700">
            <i class="fas fa-dollar-sign text-green-500 mr-2"></i> <!-- Price Icon -->
            <strong class="text-gray-800">Price:</strong> 
            <span class="ml-2 text-gray-600 font-semibold">{$coursePrice} LE</span>
        </div>

        <div class="flex items-center text-lg text-gray-700">
            <i class="fas fa-building text-yellow-500 mr-2"></i> <!-- Department Icon -->
            <strong class="text-gray-800">Department:</strong> 
            <span class="ml-2 text-gray-600 font-semibold">{$courseDepartment}</span>
        </div>

        <div class="flex items-center text-lg text-gray-700">
            <i class="fas fa-user-md text-red-500 mr-2"></i> <!-- Doctor Icon -->
            <strong class="text-gray-800">Doctor:</strong> 
            <span class="ml-2 text-gray-600 font-semibold" id="doctorName">{$courseDoctor}</span>
        </div>

        <div class="flex items-center text-lg text-gray-700">
            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i> <!-- Date Icon -->
            <strong class="text-gray-800">Start Date:</strong> 
            <span class="ml-2 text-gray-600 font-semibold">{$courseStartDate}</span>
        </div>

        <button onclick="showCourseDescription('{$courseDescription}')"
            class="w-full mt-5 bg-gradient-to-r from-blue-500 to-blue-700 
                   hover:from-blue-600 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-lg 
                   transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center">
            <i class="fas fa-eye mr-2"></i> View Description
        </button>
        
    </div>
</div>

    </div>
</div>
HTML;

    echo $courseCard;
                }
            } else {
                echo "<p class='text-lg text-gray-500 font-medium text-center py-4 bg-gray-100 rounded-lg shadow-md'>
    No courses found.
</p>
";
            }
            ?>
        </div>

        <!-- Swiper Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

   <!-- Pagination Section -->
<div class="pagination-wrapper flex flex-col items-center mt-8">
    <div class="pagination-info text-lg font-semibold text-gray-700 bg-gray-100 px-6 py-2 rounded-full shadow-md">
        Page <?= $page ?> of <?= $totalPages ?>
    </div>

    <div class="pagination-button-container flex justify-center items-center gap-6 mt-6">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&search=<?= $search ?>&sort=<?= $sort ?>"
               class="px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-500 text-white font-semibold rounded-full
                      hover:from-blue-500 hover:to-purple-500 hover:scale-110 transition-all duration-300
                      shadow-lg flex items-center gap-2 transform active:scale-95 border-2 border-transparent hover:border-white">
                <span class="material-icons text-lg animate-bounce">arrow_back</span> Previous
            </a>
        <?php endif; ?>

        <span class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-full 
                     shadow-xl text-lg transform scale-105 transition-all duration-300">
            Page <?= $page ?> of <?= $totalPages ?>
        </span>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&search=<?= $search ?>&sort=<?= $sort ?>"
               class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold rounded-full
                      hover:from-indigo-500 hover:to-blue-500 hover:scale-110 transition-all duration-300
                      shadow-lg flex items-center gap-2 transform active:scale-95 border-2 border-transparent hover:border-white">
                Next <span class="material-icons text-lg animate-bounce">arrow_forward</span>
            </a>
        <?php endif; ?>
    </div>
</div>

</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    function searchCourses(department) {
            const courses = document.querySelectorAll(".course-card");
            courses.forEach(course => {
                if (course.getAttribute("data-department") === department) {
                    course.style.display = "block";
                } else {
                    course.style.display = "none";
                }
            });
        }
        function toggleInfo() {
    document.querySelector(".text-info").classList.toggle("show");
}
function filterCourses(event) {
    event.preventDefault(); // Prevent form submission

    let searchValue = document.getElementById("search-bar").value.toLowerCase().trim();
    let priceFilter = document.getElementById("price-filter").value.trim();
    let dateFilter = document.getElementById("date-filter").value.trim();

    let courses = document.querySelectorAll(".swiper-slide");

    courses.forEach(course => {
        let title = course.querySelector("h3").innerText.toLowerCase();
        let priceText = course.querySelector(".text-gray-600").innerText.replace(" le", "").trim();
        let dateText = course.querySelector(".text-gray-600:last-child").innerText.trim();

        let price = parseFloat(priceText);
        let courseDate = new Date(dateText);

        let priceMatch = evaluateFilter(price, priceFilter, parseFloat);
        let dateMatch = evaluateFilter(courseDate.getTime(), dateFilter, (val) => new Date(val).getTime());
        let searchMatch = title.includes(searchValue);

        // Show or hide the course
        course.style.display = (searchMatch && priceMatch && dateMatch) ? "block" : "none";
    });
}

// Function to process filters with AND/OR logic
function evaluateFilter(value, filterString, parseFunction) {
    if (!filterString) return true;

    // Support AND & OR conditions
    let orConditions = filterString.split(/\s+OR\s+/i).map(cond => cond.trim());

    return orConditions.some(orCondition => {
        let andConditions = orCondition.split(/\s+AND\s+/i).map(cond => cond.trim());

        return andConditions.every(condition => {
            let match = condition.match(/^([<>]=?|=)?\s*(.+)$/);
            if (!match) return false;

            let operator = match[1] || "=";
            let filterValue = parseFunction(match[2]);

            switch (operator) {
                case ">": return value > filterValue;
                case "<": return value < filterValue;
                case ">=": return value >= filterValue;
                case "<=": return value <= filterValue;
                case "=": return value === filterValue;
                default: return false;
            }
        });
    });
}

    // Initialize Swiper
   var swiper = new Swiper('.swiper-container', {
    slidesPerView: 3,  // Show 3 slides at once
    spaceBetween: 20,   // Space between slides
    centeredSlides: true,  // Center active slide
    loop: true,  // Infinite loop
    speed: 1000,  // Smooth transition speed

    autoplay: {
        delay: 3000, // Auto-slide every 3 seconds
        disableOnInteraction: false, // Keep autoplay after user action
    },

   /*  effect: 'coverflow',
    coverflowEffect: {
        rotate: 30,  
        stretch: 50,  
        depth: 150,  
        modifier: 1,  
        slideShadows: true, 
        scale: 0.75,
        opacity: 0.8,
    }, */

    pagination: {
        el: '.swiper-pagination',
        clickable: true,  // Allow clicking on bullets
        dynamicBullets: true, // Make bullets animated
    },

    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    scrollbar: {
        el: '.swiper-scrollbar',
        draggable: true,  // Allow dragging scrollbar
    },

    keyboard: {
        enabled: true,  
        onlyInViewport: true,  
    },
    mousewheel: {
        invert: false,  // Enables mouse wheel scrolling
        forceToAxis: true,  // Prevents diagonal scroll issues
        sensitivity: 1,  // Controls scroll speed
    },
    parallax: true,  // Adds depth effect
    fadeEffect: { crossFade: true },  // Smooth crossfade transitions
    observer: true,  // Improves dynamic content loading
    observeParents: true,  // Keeps slides reactive

    breakpoints: {  // Responsive settings
        1024: { slidesPerView: 3, spaceBetween: 20 },
        768: { slidesPerView: 2, spaceBetween: 15 },
        480: { slidesPerView: 1, spaceBetween: 10 },
    },

    on: {  // Custom event listeners
        slideChange: function () {
            console.log('Slide changed to index:', this.realIndex);
        },
        reachEnd: function () {
            console.log('Reached the last slide!');
        }
    }
});


    // Course Description Popup
    function showCourseDescription(description) {
        Swal.fire({
    title: '📖 Course Description',
    html: description,
    icon: "info",
    confirmButtonText: '🎯 Close',
    
    // 🌈 Neon gradient with glassmorphism
    background: 'rgba(0, 0, 0, 0.7)', // Semi-transparent black
    color: '#fff', // White text
    backdrop: `
        rgba(0, 0, 0, 0.6) 
               url("https://source.unsplash.com/1600x900/?online-course,education")

        left top / cover no-repeat
    `, // Animated background
    width: 'auto', // Adjust width dynamically
    heightAuto: false, // Allow manual height
    padding: '30px', // More spacing inside

    // ✨ Custom animations
    showClass: {
        popup: 'animate__animated animate__flipInX animate__faster'
    },
    hideClass: {
        popup: 'animate__animated animate__hinge animate__faster'
    },

   

    // 🎨 Fully customized elements
    showCloseButton: true,
    closeButtonHtml: '❌',
    customClass: {
        popup: 'custom-swal-popup',
        title: 'custom-swal-title',
        htmlContainer: 'custom-swal-html',
        confirmButton: 'custom-swal-btn',
        closeButton: 'custom-swal-close'
    },

    // 🛠️ Apply extra styling when opened
    didOpen: () => {
        const popup = document.querySelector('.custom-swal-popup');
        popup.style.borderRadius = '20px';
        popup.style.boxShadow = '0 20px 30px rgba(8, 10, 137, 0.5)';
        popup.style.backdropFilter = 'blur(10px)';

        const btn = document.querySelector('.custom-swal-btn');
        btn.style.background = 'linear-gradient(135deg,rgb(49, 6, 20),rgb(123, 114, 12))';
        btn.style.fontSize = '20px';
        btn.style.padding = '12px 25px';
        btn.style.borderRadius = '10px';

        const closeBtn = document.querySelector('.custom-swal-close');
        closeBtn.style.color = '#ffea00';
        closeBtn.style.fontSize = '22px';
    }
});

    }

    
</script>
</body>
</html>
