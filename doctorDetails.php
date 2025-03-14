<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "project";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get doctor name from URL
$doctorName = isset($_GET['name']) ? $_GET['name'] : '';

// Fetch doctor details
$doctorSQL = "SELECT * FROM doctors WHERE name = ?";
$stmt = $conn->prepare($doctorSQL);
$stmt->bind_param("s", $doctorName);
$stmt->execute();
$doctorResult = $stmt->get_result();
$doctor = $doctorResult->fetch_assoc();
$stmt->close();

// Fetch related data from tables
$tables = ["dynamiccourses", "courses", "lectures"];
$data = [];

$data = [];
$counts = [];

foreach ($tables as $table) {
    // Fetch data and count rows in one query
    $sql = "SELECT *, (SELECT COUNT(*) FROM `$table` WHERE name = ?) AS total FROM `$table` WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $doctorName, $doctorName);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store data and count
    $data[$table] = $result->fetch_all(MYSQLI_ASSOC);
    $counts[$table] = ($result->num_rows > 0) ? $data[$table][0]['total'] : 0;

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Details - <?php echo htmlspecialchars($doctorName); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .hidden { display: none; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        tr:hover { background-color: #ddd; }
        img { max-width: 150px; height: auto; border-radius: 8px; margin-top: 5px; }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6">

<div class="w-full max-w-5xl bg-white shadow-xl rounded-2xl p-8 mx-auto">
    <!-- Doctor Name -->
    <h2 class="text-4xl font-extrabold text-center mb-6 text-gray-800">
        👨‍⚕️ Doctor: <span class="text-blue-600"><?php echo htmlspecialchars($doctorName); ?></span>
    </h2>

    <?php if ($doctor): ?>
        <!-- Specialization -->
        <p class="text-center text-lg text-gray-700 mb-4">
            🩺 Specialization: <strong class="text-gray-900"><?php echo htmlspecialchars($doctor['subject']); ?></strong>
        </p>

        <!-- Contact Info -->
        <div class="flex flex-col md:flex-row items-center justify-center gap-6">
            <!-- Phone -->
            <a href="https://wa.me/<?php echo preg_replace('/\D/', '', $doctor['phone_number']); ?>" 
               class="flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-600 transition"
               target="_blank">
                📞 <span class="font-semibold"><?php echo htmlspecialchars($doctor['phone_number']); ?></span>
            </a>

            <!-- Email -->
            <a href="mailto:<?php echo htmlspecialchars($doctor['email']); ?>" 
               class="flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition">
                ✉️ <span class="font-semibold"><?php echo htmlspecialchars($doctor['email']); ?></span>
            </a>
        </div>
    <?php else: ?>
        <p class="text-center text-red-500 text-lg font-semibold mt-4">Doctor details not found.</p>
    <?php endif; ?>
</div>


       

        <hr class="my-4">

       <!-- Buttons -->
<div class="flex flex-wrap justify-center gap-4 mb-6">
    <button class="toggle-btn bg-blue-500 text-white px-4 py-2 rounded" data-target="dynamiccourses">
        📘 Courses (<?php echo $counts['dynamiccourses'] ?? 0; ?>)
    </button>
    <button class="toggle-btn bg-green-500 text-white px-4 py-2 rounded" data-target="courses">
        📁 Files (<?php echo $counts['courses'] ?? 0; ?>)
    </button>
    <button class="toggle-btn bg-yellow-500 text-white px-4 py-2 rounded" data-target="lectures">
        📖 Lectures (<?php echo $counts['lectures'] ?? 0; ?>)
    </button>
</div>

<!-- Content Sections -->
<?php foreach ($data as $key => $items): ?>
    <div id="<?php echo htmlspecialchars($key); ?>" class="content hidden bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-4 capitalize">
            <?php echo str_replace("_", " ", $key); ?> (<?php echo $counts[$key] ?? 0; ?>)
        </h3>

        <?php if (!empty($items)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-blue-600 text-white text-left">
                            <?php 
                            // Define columns to exclude
                            $excludeColumns = ["id", "name", "course_image", "total"];
                            $columns = array_keys($items[0]);
                            foreach ($columns as $column): 
                                if (in_array(strtolower($column), $excludeColumns)) continue; 
                            ?>
                                <th class="border border-gray-400 px-4 py-3 font-semibold uppercase">
                                    <?php echo ucfirst(str_replace("_", " ", $column)); ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $index => $item): ?>
                            <tr class="<?php echo $index % 2 === 0 ? 'bg-gray-100' : 'bg-white'; ?> hover:bg-gray-200 transition">
                                <?php foreach ($item as $column => $value): ?>
                                    <?php 
                                    // Skip excluded columns
                                    if (in_array(strtolower($column), $excludeColumns)) continue; 
                                    ?>
                                    <td class="border border-gray-400 px-4 py-2 text-gray-800">
                                        <?php 
                                            // Decode JSON if needed
                                            if (is_string($value) && json_decode($value, true) !== null) {
                                                $decodedValue = json_decode($value, true);
                                                if (is_array($decodedValue)) {
                                                    $value = implode(", ", $decodedValue);
                                                }
                                            }

                                            // Handle media and files
                                            if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $value)) {
                                                echo "<img src='".htmlspecialchars($value)."' alt='Image' class='w-16 h-16 rounded-lg border border-gray-300 shadow-md'>";
                                            }
                                            elseif (preg_match('/\.(mp4|webm|ogg)$/i', $value)) {
                                                echo "<video controls class='w-32 h-20 rounded-lg shadow-md'>
                                                        <source src='".htmlspecialchars($value)."' type='video/mp4'>
                                                    </video>";
                                            }
                                            elseif (preg_match('/\.(mp3|wav|ogg)$/i', $value)) {
                                                echo "<audio controls class='w-32'>
                                                        <source src='".htmlspecialchars($value)."' type='audio/mpeg'>
                                                      </audio>";
                                            }
                                            elseif (preg_match('/\.(pdf|doc|docx|xls|xlsx)$/i', $value)) {
                                                echo "<a href='".htmlspecialchars($value)."' download class='text-blue-600 underline font-medium hover:text-blue-800 transition'>
                                                        📄 Download File
                                                    </a>";
                                            }
                                            elseif (preg_match('/<\/?[a-z][\s\S]*>/i', $value)) {
                                                echo $value; 
                                            } 
                                            else {
                                                echo htmlspecialchars($value); 
                                            }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-500 text-lg mt-4">No data available</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>


<div class="text-center mt-6">
<a href="doctors.php" 
   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md 
          hover:bg-blue-700 transition-all duration-300 ease-in-out transform hover:scale-105">
    ⬅ Back to Doctor List
</a>

</div>
</div>


    <script>
        $(document).ready(function () {
            $(".toggle-btn").click(function () {
                let target = $(this).data("target");
                $(".content").slideUp();
                $("#" + target).slideToggle();
            });
        });
    </script>

</body>
</html>
