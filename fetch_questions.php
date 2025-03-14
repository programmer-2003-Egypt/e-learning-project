<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if it's a submission request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exam_id']) && !empty($_POST)) {
    $exam_id = intval($_POST['exam_id']);
    $total_score = 0;
    $total_questions = 0;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'question_') === 0) {
            $question_id = intval(str_replace('question_', '', $key));
            $selected_option = intval($value);

            // Get the correct answer from the database
            $query = "SELECT options, correct_answer FROM questions WHERE id = ? AND exam_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $question_id, $exam_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $options = json_decode($row['options'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue; // Skip if JSON is invalid
                }

                $correct_option = intval($row['correct_answer']);
                $is_correct = ($selected_option === $correct_option) ? 1 : 0;

                // Update the questions table with the selected answer
                $update_query = "UPDATE questions SET selected_option = ?, is_correct = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("iii", $selected_option, $is_correct, $question_id);
                $update_stmt->execute();

                if ($is_correct) {
                    $total_score++;
                }
                $total_questions++;
            }
        }
    }
    

    // Calculate final score percentage
    $score_percentage = ($total_questions > 0) ? round(($total_score / $total_questions) * 100, 2) : 0;
    // Store the result in logged_exams table
    $log_query = "INSERT INTO logged_exams ( score) VALUES (?)";
    $log_stmt = $conn->prepare($log_query);
    $log_stmt->bind_param("i", $total_score);
    $log_stmt->execute();

    // Return result to the frontend
    echo "<div class='success'>✅ Exam submitted successfully!</div>";
    echo "<div style='margin-top: 10px; font-size: 18px; font-weight: bold; color: #28a745;'>🎯 Your Score: $total_score / $total_questions ($score_percentage%)</div>";

    exit;
}

// Fetch exam questions
if (isset($_GET['exam_id']) && is_numeric($_GET['exam_id'])) {
    $exam_id = intval($_GET['exam_id']);

    $question_sql = "SELECT id, question_text, question_type, options, number_questions FROM questions WHERE exam_id = ?";
    $stmt = $conn->prepare($question_sql);
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $question_result = $stmt->get_result();

    if ($question_result->num_rows > 0) {
        $first_question = $question_result->fetch_assoc();
        $number_questions = $first_question['number_questions']; // Fetch the number of questions
        $question_result->data_seek(0); // Reset pointer to fetch data again

        echo <<<HTML
        <form id='exam_$exam_id' onsubmit='submitExam($exam_id); return false;' style='width: 100%; margin: auto; background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15); font-family: Arial, sans-serif;'>
        <h1 style="color:blue;text-align:center;">Total Questions: {$number_questions}</h1>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px;">
            <thead>
                <tr style="background: linear-gradient(90deg, #007bff, #0056b3); color: white;">
                    <th style="padding: 14px; text-align: left;">Question</th>
                    <th style="padding: 14px; text-align: left;">Type</th>
                    <th style="padding: 14px; text-align: left;">Options</th>
                </tr>
            </thead>
            <tbody>
        HTML;

        while ($question = $question_result->fetch_assoc()) {
            $options = json_decode($question['options'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                continue; // Skip question if options JSON is invalid
            }

            $question_id = $question['id'];
            $question_text = htmlspecialchars($question['question_text'], ENT_QUOTES, 'UTF-8');
            $question_type = ucfirst(htmlspecialchars($question['question_type'], ENT_QUOTES, 'UTF-8'));

            echo <<<HTML
                <tr style="background: #f9f9f9; transition: 0.3s;">
                    <td style="padding: 14px; border-bottom: 1px solid #ddd; font-weight: bold; color: #333;">{$question_text}</td>
                    <td style="padding: 14px; border-bottom: 1px solid #ddd; font-weight: bold; color: #007bff;">{$question_type}</td>
                    <td style="padding: 14px; border-bottom: 1px solid #ddd;">
            HTML;

            foreach ($options as $index => $option) {
                $safe_option = htmlspecialchars($option, ENT_QUOTES, 'UTF-8');

                echo <<<HTML
                <label style="display: flex; align-items: center; padding: 12px; background: #e9ecef; border-radius: 8px; margin-bottom: 6px; cursor: pointer; transition: 0.3s; position: relative;">
                    <input type='radio' name='question_$question_id' value='$index' required style='margin-right: 12px; transform: scale(1.3); accent-color: #007bff; cursor: pointer;'>
                    <span style="font-weight: 500; color: #333;"> $safe_option </span>
                </label>
                HTML;
            }

            echo <<<HTML
                    </td>
                </tr>
            HTML;
        }

        echo <<<HTML
            </tbody>
        </table>
        <button type='submit' style='display: block; width: 100%; padding: 12px; background: linear-gradient(90deg, #28a745, #218838); color: white; font-size: 18px; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s; margin-top: 20px;'>
            Submit Answers
        </button>
        </form>
        HTML;
    } else {
        echo "<p style='text-align: center; color: #ff4444; font-size: 1.3em; font-weight: bold;'>No questions available.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
 