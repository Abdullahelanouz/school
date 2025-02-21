<?php
// Check if form is submitted

include '../db_connection.php';
//insert content

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//insert inputs and select  
$subject = $_POST['subject'];
$semester = $_POST['semester'];
$topic = $_POST['topic'];
$content = $_POST['content']; 

//insert content to database
$stmt = $conn->prepare("INSERT INTO content (subject, semester, topic, content) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $subject, $semester, $topic, $content);
$stmt->execute();
echo "<script>alert('تم إدراج المحتوى بنجاح');</script>";
}



// Fetch semesters from the database
$semestersQuery = "SELECT name FROM Semesters";
$semestersResult = $conn->query($semestersQuery);
$semesters = [];
while ($row = $semestersResult->fetch_assoc()) {
    $semesters[] = $row['name'];
}

// Fetch subjects and topics from the database
$subjectsQuery = "SELECT s.name AS subject_name, t.name AS topic_name FROM Subjects s JOIN Topics t ON s.id = t.subject_id";
$subjectsResult = $conn->query($subjectsQuery);
$subjects = [];
while ($row = $subjectsResult->fetch_assoc()) {
    $subjects[$row['subject_name']][] = $row['topic_name'];
}

// Display subjects, topics, and semesters in the form
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
  <title>لوحة تحكم المعلم</title>
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
    .form-label {
      font-weight: bold;
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
          <h2 class="mb-4">إدارة المحتوى التعليمي</h2>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="subject" class="form-label">اختر المادة</label>
              <select class="form-select" id="subject" name="subject" required>
                <?php foreach ($subjects as $subject => $topics): ?>
                  <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="semester" class="form-label">اختر الفصل الدراسي</label>
              <select class="form-select" id="semester" name="semester" required>
                <?php foreach ($semesters as $semester): ?>
                  <option value="<?php echo $semester; ?>"><?php echo $semester; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="topic" class="form-label">اختر الجزء المتفرع من المادة</label>
              <select class="form-select" id="topic" name="topic" required></select>
            </div>
            <div class="mb-3">
              <label for="video" class="form-label">رفع المحتوى</label>
              <textarea class="form-control summernote" id="summernote" name="content"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">تأكيد الاختيار ورفع الفيديو</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
  <script>
    document.getElementById('subject').addEventListener('change', function() {
      var subject = this.value;
      var topics = <?php echo json_encode($subjects); ?>;
      var topicSelect = document.getElementById('topic');
      topicSelect.innerHTML = '';
      if (topics[subject]) {
        topics[subject].forEach(function(topic) {
          var option = document.createElement('option');
          option.value = topic;
          option.textContent = topic;
          topicSelect.appendChild(option);
        });
      }
    });
    $(document).ready(function() {
      $('.summernote').summernote();
    });
  </script>
</body>
</html>
