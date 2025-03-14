<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Fetch questions for an exam
if (isset($_GET['exam_id'])) {
    $exam_id = intval($_GET['exam_id']);
    $query = "SELECT * FROM questions WHERE exam_id = $exam_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<form id='exam_$exam_id' class='exam-form'>";
        while ($question = $result->fetch_assoc()) {
            echo "<div class='question'>";
            echo "<p>" . htmlspecialchars($question['question_text']) . "</p>";

            $options = json_decode($question['options'], true);
            foreach ($options as $key => $option) {
                echo "<label>
                    <input type='radio' name='answer_{$question['id']}' value='{$key}'> 
                    $option
                </label><br>";
            }
            echo "</div>";
        }
        echo "<button type='button' onclick='submitExam($exam_id)'>Submit</button>";
        echo "</form>";
    } else {
        echo "<p>No questions found for this exam.</p>";
    }
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total_questions = 0;
    $response_html = "";

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'answer_') !== false) {
            $question_id = str_replace('answer_', '', $key);
            $selected_option = intval($value);

            $query = "SELECT correct_answer FROM questions WHERE id = $question_id";
            $result = $conn->query($query);
            $total_questions++;

            if ($result->num_rows > 0) {
                $correct_answer = $result->fetch_assoc()['correct_answer'];
                if ($correct_answer == $selected_option) {
                    $score++;
                    $response_html .= "<p style='color: green;'>Correct</p>";
                } else {
                    $response_html .= "<p style='color: red;'>Incorrect</p>";
                }
            }
        }
    }

    $response_html .= "<h3>Your Score: $score / $total_questions</h3>";
    echo $response_html;
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available exams</title>
    <style>
    /* General Styles */
    body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    margin: 0;
    padding: 20px;
    color: #333;

    .container {
        max-width: 900px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;

        h2 {
            text-align: center;
            color: #0056b3;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 10px;

            th {
                background: #007bff;
                color: white;
                padding: 12px;
                font-size: 16px;
            }

            td {
                padding: 12px;
                background: #f8f9fa;
                transition: background 0.3s;

                &:hover {
                    background: #e2e6ea;
                }
            }
        }

        .expand-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);

            &:hover {
                background: #218838;
                transform: translateY(-2px);
            }
        }

        .questions-container {
            display: none;
            padding: 15px;
            background: rgba(0, 123, 255, 0.1);
            border-left: 4px solid #007bff;
            animation: slideDown 0.5s ease-in-out;

            .question {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 10px;
            }
        }
    }

    hr {
        border: none;
        height: 1px;
        background: #ccc;
        margin: 15px 0;
    }

    .loading, .error, .success {
        text-align: center;
        font-style: italic;
        font-weight: bold;
        margin-top: 10px;
    }

    .loading { color: #007bff; }
    .error { color: red; }
    .success { color: green; }

    .timer {
        font-weight: bold;
        color: #d9534f;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .container {
            width: 90%;
        }

        th, td {
            padding: 8px;
            font-size: 14px;
        }

        .expand-btn {
            padding: 8px 12px;
            font-size: 12px;
        }
    }
}
.exam-btn {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    font-size: 16px;
    font-weight: bold;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-transform: uppercase;

    &:hover {
        background: linear-gradient(135deg, #0056b3, #003d80);
        transform: scale(1.05);
    }

    &:active {
        transform: scale(0.95);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    &:focus {
        outline: none;
        box-shadow: 0 0 5px #007bff;
    }

    // If inside a disabled container
    .disabled & {
        opacity: 0.5;
        pointer-events: none;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h2>Available Exams</h2>

    <?php
    $examQuery = "SELECT * FROM exams";
    $examResult = $conn->query($examQuery);
    ?>

    <?php if ($examResult->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Exam Title</th>
                <th>Duration (minutes)</th>
                <th>Timer</th>
                <th>Time Spent</th> <!-- New Column -->
                <th>Actions</th>
            </tr>
            <?php while ($exam = $examResult->fetch_assoc()): ?>
                <tr>
                    <td><?= $exam['id'] ?></td>
                    <td><?= htmlspecialchars($exam['title']) ?></td>
                    <td><?= $exam['duration'] ?> min</td>
                    <td id="timer_<?= $exam['id'] ?>" class="timer">--:--</td>
                    <td id="time_spent_<?= $exam['id'] ?>" class="time-spent">00:00</td> <!-- New Time Spent Column -->
                    <td>
                        <button class="exam-btn" onclick="startExam(<?= $exam['id'] ?>, <?= $exam['duration'] ?>)">
                            View Questions
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div id="questions_<?= $exam['id'] ?>" class="question-container" data-loaded="false" data-loading="false">
                            <p class="loading">Click the button to load questions...</p>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No exams found.</p>
    <?php endif; ?>

</div>

<script>
let activeTimers = {}; // Store active timers

async function startExam(examId, duration) {
    loadQuestions(examId);
    startTimer(examId, duration);
}

async function loadQuestions(examId) {
    let container = document.getElementById("questions_" + examId);

    if (container.dataset.loaded === "true") {
        container.style.display = (container.style.display === "none") ? "block" : "none";
        return;
    }

    if (container.dataset.loading === "true") return;
    container.dataset.loading = "true";

    container.innerHTML = "<p class='loading'>Loading questions...</p>";
    container.style.display = "block";

    try {
        let response = await fetch(`fetch_questions.php?exam_id=${examId}`);
        if (!response.ok) throw new Error("Failed to fetch questions.");
        
        let data = await response.text();
        container.innerHTML = data;
        container.dataset.loaded = "true";
    } catch (error) {
        container.innerHTML = "<p class='error'>⚠️ Error loading questions. Please try again.</p>";
    } finally {
        container.dataset.loading = "false";
    }
}

function startTimer(examId, duration) {
    if (activeTimers[examId]) {
        clearInterval(activeTimers[examId]); // Stop existing timer before starting a new one
    }

    let timeLeft = duration * 60; // Convert minutes to seconds
    let elapsedSeconds = 0; // Track time spent
    const timerElement = document.getElementById('timer_' + examId);

    // Create "Time Spent" div dynamically if it doesn't exist
    let timeSpentElement = document.getElementById('time_spent_' + examId);
    if (!timeSpentElement) {
        timeSpentElement = document.createElement('div');
        timeSpentElement.id = 'time_spent_' + examId;
        timeSpentElement.className = 'time-spent';
        timeSpentElement.innerText = "Time Spent: 0:00";
        timerElement.parentNode.appendChild(timeSpentElement); // Append after the timer
    }

    function updateDisplay() {
        const minutesLeft = Math.floor(timeLeft / 60);
        const secondsLeft = timeLeft % 60;
        timerElement.innerText = `${minutesLeft}:${secondsLeft.toString().padStart(2, '0')}`;

        const spentMinutes = Math.floor(elapsedSeconds / 60);
        const spentSeconds = elapsedSeconds % 60;
        timeSpentElement.innerText = `Time Spent: ${spentMinutes}:${spentSeconds.toString().padStart(2, '0')}`;
    }

    updateDisplay();

    activeTimers[examId] = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(activeTimers[examId]);
            timerElement.innerText = "Time's up!";
            submitExam(examId, elapsedSeconds);
        } else {
            timeLeft--;
            elapsedSeconds++;
            updateDisplay();
        }
    }, 1000);
}


async function submitExam(examId, timeSpent) {
    let form = document.getElementById(`exam_${examId}`);
    let formData = new FormData(form);
    formData.append("exam_id", examId);
    formData.append("time_spent", timeSpent); 

    let container = document.getElementById(`questions_${examId}`);
    container.innerHTML = "<p class='loading'>Submitting exam...</p>";

    try {
        let response = await fetch("fetch_questions.php", {
            method: "POST",
            body: formData
        });

        if (!response.ok) throw new Error("Submission failed.");
        let data = await response.text();

        container.innerHTML = `<div class='success-message'>${data}</div>`;
    } catch (error) {
        container.innerHTML = "<p class='error'>⚠️ Error submitting exam. Please try again.</p>";
    }
}
</script>


</body>
</html>
