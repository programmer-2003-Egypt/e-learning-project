<?php
/* include 'addfile.php'; */
$con = new PDO("mysql:host=localhost;dbname=project", "root", "");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_folder'])) {
    $folder_name = $_POST['folder_name'];
    $academic_level = $_POST['academic_level'];
    $term = $_POST['term'];

    // إضافة المجلد مع المستوى الأكاديمي والترم إلى قاعدة البيانات
    $stmt = $con->prepare("INSERT INTO folders (folder_name, academic_level, term) VALUES (:folder_name, :academic_level, :term)");
    $stmt->execute([
        ':folder_name' => $folder_name,
        ':academic_level' => $academic_level,
        ':term' => $term,
    ]);

    echo "تم إضافة المجلد بنجاح.";
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مجلد</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="container mx-auto mt-8 p-8 bg-white rounded-xl shadow-lg max-w-md">
    <h1 class="text-2xl font-bold text-gray-700 mb-6 text-center">إضافة مجلد جديد</h1>
    <form method="POST" class="space-y-4">
        <div>
            <label class="block text-gray-600 font-medium mb-1">اسم المجلد:</label>
            <input type="text" name="folder_name" required
                class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
        </div>

        <div>
            <label class="block text-gray-600 font-medium mb-1">المستوى الأكاديمي:</label>
            <select name="academic_level" required
                class="w-full border border-gray-300 p-3 rounded-lg bg-white focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
                <option value="2">الثانية</option>
                <option value="3">الثالثة</option>
                <option value="4">الرابعة</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-600 font-medium mb-1">اختر الترم:</label>
            <select name="term" required
                class="w-full border border-gray-300 p-3 rounded-lg bg-white focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
                <option value="الأول">الأول</option>
                <option value="الثاني">الثاني</option>
            </select>
        </div>

        <button type="submit" name="add_folder"
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition duration-300">
            إضافة المجلد
        </button>
    </form>
</div>


</body>

</html>
