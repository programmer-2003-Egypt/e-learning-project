<?php
// Database Connection
$host = "localhost";  // Change if necessary
$username = "root";   // Your DB username
$password = "";       // Your DB password
$database = "project"; // Database name

$conn = new mysqli($host, $username, $password, $database);

// Check for Connection Error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Students from Database
$sql = "SELECT id, username, phone, email FROM students"; // Adjust table/column names if needed
$result = $conn->query($sql);

// Fetch Total Student Count
$countSql = "SELECT COUNT(*) AS total_students FROM students";
$countResult = $conn->query($countSql);
$studentCount = ($countResult->num_rows > 0) ? $countResult->fetch_assoc()['total_students'] : 0;

// Store Students in an Array
$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-600 to-purple-700 p-6">

    <!-- 📢 Students Count Display -->
    <div class="mb-6 p-4 bg-white text-blue-800 font-bold text-lg rounded-lg shadow-lg">
        🎓 Total Students: <span class="text-blue-600"><?php echo $studentCount; ?></span>
    </div>

    <!-- 🔍 Search Bar -->
    <input type="text" id="searchInput" onkeyup="filterStudents()" 
        class="w-full max-w-md px-4 py-2 mb-6 text-gray-800 rounded-md shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
        placeholder="🔎 Search Students by Name...">

    <!-- 🏫 Student List Table -->
    <div class="w-full max-w-5xl bg-white shadow-2xl rounded-lg overflow-hidden">
        <table id="studentsTable" class="w-full border-collapse">
            <thead class="bg-blue-500 text-white text-lg">
                <tr>
                    <th class="py-3 px-5 text-left">ID</th>
                    <th class="py-3 px-5 text-left">Username</th>
                    <th class="py-3 px-5 text-left">Phone</th>
                    <th class="py-3 px-5 text-left">Email</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="py-3 px-5"><?php echo $student['id']; ?></td>
                            <td class="py-3 px-5 font-semibold"><?php echo htmlspecialchars($student['username']); ?></td>
                            <td class="py-3 px-5"><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td class="py-3 px-5"><?php echo htmlspecialchars($student['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // 🔍 Filter Students in Table
        function filterStudents() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("studentsTable"); 
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header
                let nameCell = rows[i].getElementsByTagName("td")[1]; // Assuming Name is in the 2nd column (index 1)
                if (nameCell) {
                    let name = nameCell.textContent.toLowerCase();
                    rows[i].style.display = name.includes(input) ? "" : "none";
                }
            }
        }
    </script>

</body>
</html>

