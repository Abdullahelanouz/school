<?php
// Connect to the database
include 'db_connection.php';

// Fetch semesters from the database
$semestersQuery = "SELECT id, name FROM Semesters";
$semestersResult = $conn->query($semestersQuery);
$semesters = [];
while ($row = $semestersResult->fetch_assoc()) {
    $semesters[] = $row;
}

// Fetch subjects from the database
$subjectsQuery = "SELECT id, name FROM Subjects";
$subjectsResult = $conn->query($subjectsQuery);
$subjects = [];
while ($row = $subjectsResult->fetch_assoc()) {
    $subjects[] = $row;
}

// Initialize tasks, questions, and answers arrays
$tasks = [];
$questions = [];
$answers = [];

// Check if form is submitted for filtering tasks
if (isset($_POST['semester']) && isset($_POST['subject'])) {
    $selectedSemester = $_POST['semester'];
    $selectedSubject = $_POST['subject'];

    // Fetch tasks based on selected semester and subject
    $tasksQuery = "SELECT a.id, a.title, a.description, a.due_date FROM Assignments a JOIN Courses c ON a.created_by = c.created_by JOIN Enrollments e ON c.id = e.course_id WHERE c.name = ? AND e.semester_id = ?";
    $stmt = $conn->prepare($tasksQuery);
    $stmt->bind_param("si", $selectedSubject, $selectedSemester);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    // Fetch questions and answers for each task
    foreach ($tasks as $task) {
        $questionsQuery = "SELECT id, question FROM Questions WHERE task_id = ?";
        $stmt = $conn->prepare($questionsQuery);
        $stmt->bind_param("i", $task['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $taskQuestions = [];
        while ($row = $result->fetch_assoc()) {
            $taskQuestions[] = $row;
            // Fetch answers for each question
            $answersQuery = "SELECT answer, is_correct FROM Answers WHERE question_id = ?";
            $stmt = $conn->prepare($answersQuery);
            $stmt->bind_param("i", $row['id']);
            $stmt->execute();
            $answerResult = $stmt->get_result();
            $questionAnswers = [];
            while ($answerRow = $answerResult->fetch_assoc()) {
                $questionAnswers[] = $answerRow;
            }
            $answers[$row['id']] = $questionAnswers;
        }
        $questions[$task['id']] = $taskQuestions;
    }
}

// Check if a new question with answers is being added
if (isset($_POST['questions']) && isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];
    $questions = $_POST['questions'];
    $answers = $_POST['answers'];
    $correctAnswers = $_POST['correct_answer'];

    foreach ($questions as $index => $newQuestion) {
        // Insert the new question
        $insertQuestionQuery = "INSERT INTO Questions (task_id, question) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuestionQuery);
        $stmt->bind_param("is", $taskId, $newQuestion);
        $stmt->execute();
        $questionId = $stmt->insert_id;

        // Insert answers
        foreach ($answers[$index] as $answerIndex => $answer) {
            $isCorrect = ($answerIndex == $correctAnswers[$index]) ? 1 : 0;
            $insertAnswerQuery = "INSERT INTO Answers (question_id, answer, is_correct) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertAnswerQuery);
            $stmt->bind_param("isi", $questionId, $answer, $isCorrect);
            $stmt->execute();
        }
    }
    echo "<script>alert('تم إضافة الأسئلة والإجابات بنجاح');</script>";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>صفحة المهام</title>
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">صفحة المهام</h1>
    <form action="" method="post">
      <div class="mb-3">
        <label for="semester" class="form-label">اختر الفصل الدراسي</label>
        <select class="form-select" id="semester" name="semester" required>
          <?php foreach ($semesters as $semester): ?>
            <option value="<?php echo $semester['id']; ?>"><?php echo $semester['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="subject" class="form-label">اختر المادة</label>
        <select class="form-select" id="subject" name="subject" required>
          <?php foreach ($subjects as $subject): ?>
            <option value="<?php echo $subject['name']; ?>"><?php echo $subject['name']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    <div class="mt-4">
      <h2>المهام</h2>
      <ul class="list-group">
        <?php foreach ($tasks as $task): ?>
          <li class="list-group-item">
            <strong>العنوان:</strong> <?php echo htmlspecialchars($task['title']); ?><br>
            <strong>الوصف:</strong> <?php echo htmlspecialchars($task['description']); ?><br>
            <strong>تاريخ الاستحقاق:</strong> <?php echo htmlspecialchars($task['due_date']); ?><br>
            <h4>الأسئلة:</h4>
            <ul>
              <?php foreach ($questions[$task['id']] as $question): ?>
                <li>
                  <?php echo htmlspecialchars($question['question']); ?>
                  <ul>
                    <?php foreach ($answers[$question['id']] as $answer): ?>
                      <li<?php if ($answer['is_correct']) echo ' style="font-weight: bold;"'; ?>>
                        <?php echo htmlspecialchars($answer['answer']); ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </li>
              <?php endforeach; ?>
                    </ul>
              <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
              <div class="mb-3">
                <label for="questions" class="form-label">إضافة أسئلة جديدة</label>
                <div id="questions-container">
                  <div class="question-item mb-3">
                    <input type="text" class="form-control mb-2" name="questions[]" placeholder="السؤال" required>
                    <div class="answers-container">
                      <input type="text" class="form-control mb-2" name="answers[0][]" placeholder="الإجابة 1" required>
                      <input type="text" class="form-control mb-2" name="answers[0][]" placeholder="الإجابة 2" required>
                      <input type="text" class="form-control mb-2" name="answers[0][]" placeholder="الإجابة 3" required>
                      <input type="text" class="form-control mb-2" name="answers[0][]" placeholder="الإجابة 4" required>
                      <input type="hidden" name="correct_answer[0]" value="0">
                    </div>
                  </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addQuestion()">إضافة سؤال آخر</button>
              </div>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  </form>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
  function addQuestion() {
    const container = document.getElementById('questions-container');
    const index = container.children.length;
    const questionItem = document.createElement('div');
    questionItem.className = 'question-item mb-3';
    questionItem.innerHTML = `
      <input type="text" class="form-control mb-2" name="questions[]" placeholder="السؤال" required>
      <div class="answers-container">
        <input type="text" class="form-control mb-2" name="answers[${index}][]" placeholder="الإجابة 1" required>
        <input type="text" class="form-control mb-2" name="answers[${index}][]" placeholder="الإجابة 2" required>
        <input type="text" class="form-control mb-2" name="answers[${index}][]" placeholder="الإجابة 3" required>
        <input type="text" class="form-control mb-2" name="answers[${index}][]" placeholder="الإجابة 4" required>
        <input type="hidden" name="correct_answer[${index}]" value="0">
      </div>
    `;
    container.appendChild(questionItem);
  }
  </script>
</body>
</html> 