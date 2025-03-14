<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Exam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center min-h-screen p-6">

<div class="bg-white p-8 rounded-xl shadow-lg max-w-lg w-full transition-all duration-300 hover:scale-105 hover:shadow-2xl mx-auto">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">
            <i class="fas fa-edit text-blue-500"></i> Create Exam
        </h2>

        <form id="examForm">
            <label for="exam_title" class="block font-semibold text-gray-700 mb-1">Exam Title:</label>
            <input type="text" id="exam_title" name="exam_title" required
                class="w-full p-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-300">
            
            <label for="duration" class="block font-semibold text-gray-700 mt-3 mb-1">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" required
                class="w-full p-3 border-2 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-300">

            <h3 class="text-xl font-bold text-gray-700 mt-6">
                <i class="fas fa-question-circle text-yellow-500"></i> Questions
            </h3>
            <div id="questionsContainer" class="mt-4"></div>

            <button type="button" onclick="addQuestion()"
                class="mt-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                <i class="fas fa-plus-circle"></i> Add Question
            </button>

            <button type="submit"
                class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition-all duration-300">
                <i class="fas fa-paper-plane"></i> Submit Exam
            </button>
        </form>
    </div>


    <script>
     let questionIndex = 0;

function addQuestion() {
    const container = document.getElementById("questionsContainer");

    const questionDiv = document.createElement("div");
    questionDiv.className = "question bg-white p-6 rounded-lg mt-6 border-l-4 border-blue-500 shadow-md transition-all duration-300 hover:shadow-lg";
    questionDiv.setAttribute("data-id", questionIndex);
    questionDiv.innerHTML = `
        <div class="flex justify-between items-center">
            <label class="block font-semibold text-gray-700 text-lg flex items-center">
                <i class="fas fa-question-circle text-blue-500 mr-2"></i> 
                <span>Question <span class="question-number">${questionIndex + 1}</span>:</span>
            </label>
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeQuestion(${questionIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <input type="text" name="questions[${questionIndex}][text]" required
            class="w-full p-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-300">

        <label class="block font-semibold text-gray-700 mt-3 flex items-center">
            <i class="fas fa-list-ul text-green-500 mr-2"></i> Question Type:
        </label>
        <select name="questions[${questionIndex}][type]" onchange="toggleQuestionType(${questionIndex}, this.value)" 
            class="w-full p-2 border-2 rounded-lg focus:ring-2 focus:ring-green-400 transition-all">
            <option value="multiple">Multiple Choice</option>
            <option value="truefalse">True / False</option>
        </select>

        <div id="multipleChoice-${questionIndex}">
            <label class="block font-semibold text-gray-700 mt-3 flex items-center">
                <i class="fas fa-list-ul text-green-500 mr-2"></i> Options:
            </label>
            <div class="grid grid-cols-1 gap-3">
                <input type="text" name="questions[${questionIndex}][options][]" placeholder="Option 1" required 
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400 transition-all">
                <input type="text" name="questions[${questionIndex}][options][]" placeholder="Option 2" required 
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400 transition-all">
                <input type="text" name="questions[${questionIndex}][options][]" placeholder="Option 3" required 
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400 transition-all">
                <input type="text" name="questions[${questionIndex}][options][]" placeholder="Option 4" required 
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-green-400 transition-all">
            </div>
            <label class="block font-semibold text-gray-700 mt-3 flex items-center">
                <i class="fas fa-check-circle text-purple-500 mr-2"></i> Correct Answer:
            </label>
            <input type="text" name="questions[${questionIndex}][correct]" required 
                class="w-full p-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 transition-all">
        </div>

        <div id="trueFalse-${questionIndex}" class="hidden">
            <label class="block font-semibold text-gray-700 mt-3 flex items-center">
                <i class="fas fa-check-circle text-purple-500 mr-2"></i> Correct Answer:
            </label>
            <input type="text" name="questions[${questionIndex}][correct_tf]" required 
                class="w-full p-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 transition-all" 
                placeholder="Enter 'True' or 'False'">
        </div>
    `;

    container.appendChild(questionDiv);
    questionIndex++;

    makeQuestionsSortable();
}

function toggleQuestionType(index, type) {
    document.getElementById(`multipleChoice-${index}`).classList.toggle("hidden", type === "truefalse");
    document.getElementById(`trueFalse-${index}`).classList.toggle("hidden", type === "multiple");
}

function removeQuestion(index) {
    const questionToRemove = document.querySelector(`.question[data-id="${index}"]`);
    if (questionToRemove) {
        questionToRemove.remove();
        updateQuestionNumbers();
    }
}

function updateQuestionNumbers() {
    document.querySelectorAll(".question").forEach((question, index) => {
        question.querySelector(".question-number").textContent = index + 1;
    });
}

function makeQuestionsSortable() {
    new Sortable(document.getElementById("questionsContainer"), {
        animation: 150,
        onEnd: () => updateQuestionNumbers()
    });
}

document.getElementById("examForm").addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch("insert_exam.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: data.status === "success" ? "success" : "error",
            title: data.status === "success" ? "Success!" : "Error",
            text: data.message
        });
    })
    .catch(error => console.error("Error:", error));
});
</script>
    </script>
</body>
</html>
