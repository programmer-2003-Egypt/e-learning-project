<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logged-in users
$stmt = $conn->prepare("SELECT user_email, user_name, phone, login_time,title_exam FROM logged_exams ORDER BY login_time DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Logged in Exams</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-4 text-center text-indigo-700">Users Logged in Exams</h2>

        <!-- Search Box -->
        <div class="flex justify-center mb-4">
            <input type="text" id="searchBox" placeholder="Search by name or email..." 
                   class="p-2 w-1/3 border border-gray-300 rounded-lg shadow-sm">
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-md rounded-lg">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Phone</th>
                        <th class="py-3 px-4">Login Date</th>
                        <th class="py-3 px-4">Login Time</th>
                        <th class="py-3 px-4">title-exam</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): 
                            $dateTime = new DateTime($row['login_time']);
                            $login_date = $dateTime->format('Y-m-d'); // Extract Date
                            $login_time = $dateTime->format('h:i:s A'); // Extract Time in 12-hour format
                        ?>
                            <tr class="border-b hover:bg-gray-100 transition duration-200">
                                <td class="py-2 px-4"><?= htmlspecialchars($row['user_email']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($row['user_name']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($row['phone']) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($login_date) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($login_time) ?></td>
                                <td class="py-2 px-4"><?= htmlspecialchars($row['title_exam']) ?></td>
                              
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">No users logged in exams.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Live search functionality
        $(document).ready(function () {
            $("#searchBox").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#userTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

</body>
</html>
<?php $stmt->close(); $conn->close(); ?>
