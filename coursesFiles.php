<?php
include 'connect.php'; // Connect to the database

// Fetch courses from the database
$sql = "SELECT id, description, file_path FROM courses WHERE file_path LIKE '%.pdf'"; // Filter PDF only
$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
$count_sql = "SELECT COUNT(*) as total FROM courses";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_files = $count_row['total'];
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الكورسات</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css">
    <style>
        body {
      body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to bottom, #1e3a8a, #2563eb);
    transition: background 0.5s ease;

    &.dark {
        background: linear-gradient(to bottom, #2d3748, #4a5568);
        color: #e2e8f0;

        header {
            background: linear-gradient(to right, #2c5282, #2b6cb0);
        }

        .btn {
            background: linear-gradient(to right, #2b6cb0, #1a365d);
        }

        .course-card {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        .pagination {
            button {
                background-color: #4a5568;
                color: #e2e8f0;
            }
        }
    }
}

header {
    background: linear-gradient(to right, #4f46e5, #6d28d9);
}

.btn {
    background: linear-gradient(to right, #3b82f6, #1e40af);
    color: white;
    transition: all 0.3s;
}

.course-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.icon {
    color: #3b82f6;
    margin-right: 8px;
}
@keyframes gradient-animation {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-animation 6s ease infinite;
    }
    .focus:shadow-neon {
        box-shadow: 0px 0px 12px rgba(0, 128, 255, 0.5);
    }
    </style>
</head>
<body>
<!-- Header Section with Animated Gradient & Shadow -->
<header class="py-10 text-center bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-700 text-white shadow-2xl rounded-b-3xl animate-gradient">
    <h1 class="text-5xl font-extrabold mb-2 drop-shadow-lg">دورات تعلم البرمجة والفيزياء</h1>
    <p class="text-xl opacity-95">إكتشف كورساتنا المميزة في البرمجة والفيزياء لتطوير مهاراتك.</p>
</header>
<?php
echo "<p class='text-center text-2xl font-bold text-white bg-green-500 p-4 rounded-lg shadow-lg'>
         Total Files: <span class='text-yellow-300'>$total_files</span>
      </p>";
?>

<!-- Search Bar with Neon Glow -->
<div class="container mx-auto my-8 px-4 text-center">
    <input id="search-bar" type="text"
        class="w-3/4 md:w-2/3 p-3 text-lg bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-2xl shadow-lg 
        focus:ring-4 focus:ring-blue-400 dark:focus:ring-blue-600 transition-all duration-300 focus:shadow-neon"
        placeholder="🔍 ابحث عن الدورة...">
</div>

<!-- Course List Grid with Animated Cards -->
<div id="course-list" class="container mx-auto mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 px-6">
    <!-- Dynamic course cards will be injected here -->
</div>

<!-- Pagination with Hover & Scale Effects -->
<div id="pagination" class="flex justify-center space-x-6 mt-8">
    <button class="px-5 py-3 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 
        text-white font-semibold transition-all duration-300 shadow-md hover:scale-105 active:scale-95">
        ◀ السابق
    </button>
    <button class="px-5 py-3 rounded-lg bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 
        text-white font-semibold transition-all duration-300 shadow-md hover:scale-105 active:scale-95">
        التالي ▶
    </button>
</div>

<!-- Footer Section with Animated Gradient & Shadow -->
<footer class="bg-gray-900 text-white py-8 mt-12 rounded-t-3xl shadow-inner animate-gradient">
    <div class="container mx-auto text-center">
        <p class="text-lg">&copy; 2024 جميع الحقوق محفوظة. جميع الدورات مقدمة من قبل
            <a href="#" class="text-blue-400 hover:underline transition-all duration-300">دورة تعلم البرمجة والفيزياء</a>.
        </p>
    </div>
</footer>



<script>
    class CourseManager {
        static courses = <?php echo json_encode($courses); ?>;
        static currentPage = 1;
        static itemsPerPage = 6;

        static generateCourseHTML(course) {
            return `
            <div class="relative bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 course-card"
    aria-labelledby="course-${course.id}">

    <!-- Decorative Element -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-transparent rounded-xl pointer-events-none"></div>

    <!-- Course Title -->
    <h3 id="course-${course.id}" class="text-xl font-semibold mb-3 flex items-center space-x-3 text-gray-900 dark:text-gray-100">
        <i class="fa fa-book text-blue-600 dark:text-blue-400"></i>
        <span>${course.doctor_name ??"unknown"}</span>
    </h3>

    <!-- Course Description -->
    <p class="text-gray-700 dark:text-gray-300 mb-3 flex items-center space-x-3">
        <i class="fa fa-info-circle text-gray-500 dark:text-gray-400"></i>
        <span>${course.description}</span>
    </p>

    <!-- File Actions -->
    ${course.file_path ? `
        <div class="flex items-center space-x-4">
            <button class="relative px-5 py-2.5 rounded-lg shadow-md bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-white transition-all duration-300 flex items-center space-x-2 group"
            onclick="CourseManager.previewFile('${course.file_path}')">
                <i class="fa fa-eye"></i>
                <span>عرض الملف</span>
                <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    Preview File
                </span>
            </button>
            <button class="relative px-5 py-2.5 rounded-lg shadow-md bg-green-500 hover:bg-green-600 dark:bg-green-700 dark:hover:bg-green-800 text-white transition-all duration-300 flex items-center space-x-2 group"
            onclick="CourseManager.downloadFile('${course.file_path}')">
                <i class="fa fa-download"></i>
                <span>تحميل الملف</span>
                <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs text-gray-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    Download File
                </span>
            </button>
        </div>
    ` : ''}
</div>


            `;
        }

        static displayCourses() {
            const startIndex = (CourseManager.currentPage - 1) * CourseManager.itemsPerPage;
            const endIndex = startIndex + CourseManager.itemsPerPage;
            const currentCourses = CourseManager.courses.slice(startIndex, endIndex);
            document.getElementById('course-list').innerHTML = currentCourses.map(CourseManager.generateCourseHTML).join('');
            CourseManager.updatePagination();
        }

        static updatePagination() {
            const pageCount = Math.ceil(CourseManager.courses.length / CourseManager.itemsPerPage);
            let paginationHTML = '';
            for (let i = 1; i <= pageCount; i++) {
                paginationHTML += `<button class="px-4 py-2 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105 
    ${i === CourseManager.currentPage ? 'bg-blue-600 text-white font-bold' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'}
    hover:bg-blue-500 dark:hover:bg-blue-400"
    onclick="CourseManager.changePage(${i})">
    ${i}
</button>
`;
            }
            document.getElementById('pagination').innerHTML = paginationHTML;
        }

        static changePage(pageNumber) {
            CourseManager.currentPage = pageNumber;
            CourseManager.displayCourses();
        }

        static filterCourses() {
            const searchTerm = document.getElementById('search-bar').value.toLowerCase();
            CourseManager.courses = <?php echo json_encode($courses); ?>.filter(course => 
                course.name.toLowerCase().includes(searchTerm) || 
                course.description.toLowerCase().includes(searchTerm)
            );
            CourseManager.currentPage = 1;
            CourseManager.displayCourses();
        }

        static previewFile(filePath) {
            const fileExtension = filePath.split('.').pop().toLowerCase();
            switch (fileExtension) {
                case 'pdf':
                    CourseManager.previewPDF(filePath);
                    break;
                default:
                    Swal.fire({
                        icon: 'error',
                        title: 'نوع غير مدعوم',
                        text: 'عذرًا، نوع الملف غير مدعوم للمراجعة.'
                    });
            }
        }

        static previewPDF(filePath) {
            Swal.fire({
                title: 'عرض ملف PDF',
                html: `
            <div class="flex flex-col items-center w-full max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 space-y-6">
    
    <!-- Page Number Input -->
    <div class="w-full relative">
        <input type="number" id="page-number" placeholder="رقم الصفحة" min="1"
            class="w-full px-5 py-3 text-lg text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-4 focus:ring-blue-400 dark:focus:ring-blue-600 focus:border-transparent transition-all duration-300 shadow-sm hover:shadow-lg placeholder-gray-500 dark:placeholder-gray-400 pr-12" />
        
        <!-- Icon inside input -->
        <i class="fa fa-file-alt absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400 text-lg"></i>
    </div>

    <!-- PDF Canvas with Overflow Scroll -->
    <div class="relative w-full h-[500px] max-h-[80vh] overflow-auto border border-gray-300 dark:border-gray-600 rounded-2xl shadow-md bg-gradient-to-b from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 p-2">
        <canvas id="pdf-canvas" class="w-full h-full rounded-lg shadow-inner"></canvas>
        
      
        </div>
    </div>

</div>



                `,
                didOpen: () => {
                    const pdfUrl = filePath;
                    pdfjsLib.getDocument(pdfUrl).promise.then(pdfDoc => {
                        const renderPage = (pageNum) => {
                            pdfDoc.getPage(pageNum).then(page => {
                                const scale = 1.5;
                                const viewport = page.getViewport({ scale });
                                const canvas = document.getElementById('pdf-canvas');
                                const context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                page.render({ canvasContext: context, viewport });
                            });
                        };

                        renderPage(1); // Default page

                        document.getElementById('page-number').addEventListener('input', (e) => {
                            const pageNum = parseInt(e.target.value, 10);
                            if (pageNum && pageNum > 0) {
                                renderPage(pageNum);
                            }
                        });
                    });
                }
            });
        }

        static downloadFile(filePath) {
            Swal.fire({
                title: 'Downloading...',
                html: `<div class="flex flex-col items-center justify-center w-full max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-300 dark:border-gray-700 text-center space-y-4">
    
    <!-- Loading Message -->
    <p class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center space-x-3">
        <i class="fa fa-spinner animate-spin text-blue-500 dark:text-blue-400 text-xl"></i>
        <span>جاري تحميل ملف الدورة. من فضلك انتظر...</span>
    </p>

    <!-- Stylish Progress Bar Container -->
    <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full h-6 shadow-inner overflow-hidden relative">
        <!-- Animated Progress Fill -->
        <div id="progress-bar" class="absolute left-0 top-0 h-full bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-400 dark:to-blue-500 rounded-full transition-all duration-500 ease-in-out" style="width: 0%;">
        </div>
    </div>

</div>
`,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    const progressElement = document.querySelector('progress');
                    const xhr = new XMLHttpRequest();
                    xhr.open("GET", filePath, true);
                    xhr.responseType = "blob";

                    xhr.onprogress = function (e) {
                        if (e.lengthComputable) {
                            let progress = (e.loaded / e.total) * 100;
                            progressElement.value = progress;
                        }
                    };

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            const link = document.createElement('a');
                            const blob = xhr.response;
                            const url = URL.createObjectURL(blob);
                            link.href = url;
                            link.download = filePath.split('/').pop();
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            Swal.fire({
                                title: 'تم التحميل!',
                                text: 'تم تحميل ملف الدورة بنجاح.',
                                icon: 'success',
                                confirmButtonText: 'موافق'
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء تحميل الملف.',
                                icon: 'error',
                                confirmButtonText: 'موافق'
                            });
                        }
                    };

                    xhr.onerror = function () {
                        Swal.fire({
                            title: 'خطأ',
                            text: 'فشل تحميل الملف.',
                            icon: 'error',
                            confirmButtonText: 'موافق'
                        });
                    };

                    xhr.send();
                }
            });
        }

        static init() {
            CourseManager.displayCourses();
            document.getElementById('search-bar').addEventListener('input', CourseManager.filterCourses);
        }
    }

    CourseManager.init();

    function toggleDarkMode() {
        document.body.classList.toggle('dark');
    }
</script>

</body>
</html>
