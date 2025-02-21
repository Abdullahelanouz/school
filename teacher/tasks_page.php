<?php
include '../db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $answers = $_POST['answers'];
    $correct = $_POST['correct'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];
// Debug: Print the semester value
echo "Submitted Semester: " . htmlspecialchars($semester) . "<br>";
    // Insert question
    $stmt = $conn->prepare("INSERT INTO questions (question, semester, subject) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $question, $semester, $subject);
    $stmt->execute();
    $question_id = $stmt->insert_id;

    // Insert answers
    foreach ($answers as $index => $answer) {
        $is_correct = ($index == $correct) ? 1 : 0;
        $stmt = $conn->prepare("INSERT INTO answers (question_id, answer, is_correct) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $question_id, $answer, $is_correct);
        $stmt->execute();
    }

    // Fetch all questions and answers
    // $questions = [];
    // $answers = [];
    // $sql = "SELECT q.id, q.question, a.answer, a.is_correct 
    //         FROM questions q 
    //         JOIN answers a ON q.id = a.question_id";  
    // $result = $conn->query($sql);
    // while ($row = $result->fetch_assoc()) {
    //     $questions[] = $row['question'];
    //     $answers[] = $row['answer'];
    // }

    
    echo "<div class='alert alert-success mt-3'>Question and answers added successfully.</div>";
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة الأسئلة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .dashboard-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            background: #2d3e50;
            color: white;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }
        .sidebar a:hover {
            background: #1b2838;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h3 class="text-center">لوحة التحكم</h3>
                <a href="#">الرئيسية</a>
        <a href="teacher_page.php">إدارة المحتوى التعليمي</a>
        <a href="tasks_page.php">إضافة الأسئلة</a>
        <a href="view.php">عرض المحتوى</a>
        <a href="logout.php">تسجيل الخروج</a>
            </div>
            <div class="col-md-10 p-4">
                <div class="dashboard-container">
                    <h2 class="mb-4">إضافة سؤال جديد</h2>
                    <form action="tasks_page.php" method="post">
                        <div class="mb-3">
                            <label for="question" class="form-label">السؤال</label>
                            <input type="text" class="form-control" id="question" name="question" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الإجابات</label>
                            <input type="text" class="form-control" name="answers[]" placeholder="الإجابة 1" required>
                            <input type="text" class="form-control mt-2" name="answers[]" placeholder="الإجابة 2" required>
                            <input type="text" class="form-control mt-2" name="answers[]" placeholder="الإجابة 3" required>
                            <input type="text" class="form-control mt-2" name="answers[]" placeholder="الإجابة 4" required>
                        </div>
                        <div class="mb-3">
                            <label for="correct" class="form-label">الإجابة الصحيحة</label>
                            <select class="form-select" id="correct" name="correct" required>
                                <option value="0">الإجابة 1</option>
                                <option value="1">الإجابة 2</option>
                                <option value="2">الإجابة 3</option>
                                <option value="3">الإجابة 4</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">الفصل الدراسي</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <?php
                                $sql = "SELECT name FROM semesters";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">المادة</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <?php
                                $sql = "SELECT id, name FROM subjects";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">إضافة السؤال</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
