<?php
$con = new PDO("mysql:host=localhost;dbname=project", "root", "");
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الملفات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition-all">

    <!-- Header -->
    <header class="bg-gray-900 w-full shadow-md z-10 text-white fixed top-0 left-0 right-0">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <h1 class="text-2xl font-bold tracking-wide">📂 عرض الملفات</h1>
            <div class="relative">
                <!-- Notification Icon with Badge -->
                <button class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s3-1 3-4V9c0-2.21-1.79-4-4-4s-4 1.79-4 4v9c0 3-3 4-3 4"></path>
                    </svg>
                    <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-600 ring-2 ring-white animate-ping"></span>
                </button>
            </div>
        </nav>
    </header>

    <!-- Academic Level Selection -->
    <section class="container mx-auto mt-24 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg transition-all">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">📖 اختر المستوى الأكاديمي:</h2>
        <form method="GET" action="" class="space-y-4">
            <select name="academic_level" required class="w-full p-3 border rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                <option value="" disabled selected>اختر المستوى</option>
                <option value="2">السنة الثانية</option>
                <option value="3">السنة الثالثة</option>
                <option value="4">السنة الرابعة</option>
            </select>
            <div class="text-center">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-md hover:scale-105 transition-transform duration-300 shadow-md">
                    🔍 عرض الملفات
                </button>
            </div>
        </form>
    </section>

    <!-- File Search & Filter -->
    <section class="container mx-auto mt-8 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg transition-all">
        <div class="flex flex-wrap md:flex-nowrap justify-between items-center mb-4 space-y-2 md:space-y-0">
            <div class="flex flex-wrap md:flex-nowrap space-x-2 w-full">
                <input type="text" class="flex-1 p-3 border rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="🔎 بحث عن الملفات" id="searchInput">
                <select class="p-3 border rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" id="filterSelect">
                    <option value="date">📅 تصفية حسب التاريخ</option>
                    <option value="size">📦 تصفية حسب الحجم</option>
                </select>
            </div>
            <button id="searchBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center transition-all">
                <i class="fas fa-search mr-2"></i> بحث
            </button>
        </div>
    </section>

    <!-- Available Files Display -->
    <section class="container mx-auto mt-8 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg transition-all">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-200">📂 الملفات المتاحة:</h2>
        <?php
        if (isset($_GET['academic_level'])) {
            $academic_level = $_GET['academic_level'];
            $terms = ['الأول', 'الثاني'];
            foreach ($terms as $term) {
                $files_exist_stmt = $con->prepare("SELECT COUNT(files.id) AS file_count FROM files JOIN folders ON files.folder_id = folders.id WHERE folders.academic_level = :academic_level AND files.term = :term");
                $files_exist_stmt->execute([':academic_level' => $academic_level, ':term' => $term]);
                $file_count = $files_exist_stmt->fetch(PDO::FETCH_ASSOC)['file_count'];

                if ($file_count > 0) {
                    echo "<div class='bg-gray-300 p-4 rounded-md shadow-md mb-6'>
                            <h3 class='text-lg font-bold mb-4'>الترم $term</h3>";

                    $folders_stmt = $con->prepare("SELECT id, folder_name FROM folders WHERE academic_level = :academic_level");
                    $folders_stmt->execute([':academic_level' => $academic_level]);
                    $folders = $folders_stmt->fetchAll(PDO::FETCH_ASSOC);

                    if ($folders) {
                        echo "<div class='grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'>";
                        foreach ($folders as $folder) {
                            $files_stmt = $con->prepare("SELECT file_name FROM files WHERE folder_id = :folder_id AND term = :term");
                            $files_stmt->execute([':folder_id' => $folder['id'], ':term' => $term]);
                            $files = $files_stmt->fetchAll(PDO::FETCH_ASSOC);

                            echo "<div class='bg-gray-100 border border-gray-300 rounded-md shadow-lg p-4 transition-all hover:bg-gray-200'>
                                    <h4 class='font-bold text-blue-600 mb-2'>{$folder['folder_name']}</h4>";
                            if ($files) {
                                echo "<ul class='space-y-2'>";
                                foreach ($files as $file) {
                                    echo "<li class='flex items-center space-x-2'>
                                            <a href='uploads/{$academic_level}/{$folder['folder_name']}/$term/{$file['file_name']}' 
                                               target='_blank' class='text-blue-500 hover:underline'>
                                                <span class='inline-block w-4 h-4 bg-blue-500 rounded-full'></span> 
                                                {$file['file_name']}
                                            </a>
                                          </li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p class='text-gray-500'>لا توجد ملفات في هذا المجلد.</p>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p class='text-gray-500'>لا توجد مجلدات متاحة لهذا المستوى الأكاديمي.</p>";
                    }
                    echo "</div>";
                }
            }
        } else {
            echo "<p class='text-gray-600'>يرجى اختيار المستوى الأكاديمي لعرض الملفات.</p>";
        }
        ?>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 text-center mt-16">
        <p>&copy; 2024 جامعة الزقازيق. جميع الحقوق محفوظة.</p>
    </footer>

    <script>
        // JavaScript for triggering the SweetAlert modal
        document.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                // Open SweetAlert confirmation modal
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "هل تريد تحميل هذا الملف؟",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، تحميل!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link.href; // Redirect to file URL
                    }
                });
            });
        });

        // Search functionality
        const searchInput = document.getElementById("searchInput");
        const filterSelect = document.getElementById("filterSelect");
        const searchBtn = document.getElementById("searchBtn");

        searchBtn.addEventListener("click", function () {
            const query = searchInput.value;
            const filter = filterSelect.value;

            // Show SweetAlert for search query
            Swal.fire({
                title: 'بحث قيد التنفيذ',
                text: `البحث عن: ${query} حسب ${filter}`,
                icon: 'info',
                showConfirmButton: false,
                timer: 2000
            });

            // Add search logic here (AJAX, filtering, etc.)
        });
    </script>

</body>

</html>
