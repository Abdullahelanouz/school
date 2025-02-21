<?php
include '../db_connection.php';

// Fetch student data
$sql = "SELECT students.name AS student_name, grades.grade, subjects.name AS subject_name 
        FROM students
        JOIN grades ON students.id = grades.student_id
        JOIN subjects ON grades.subject_id = subjects.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درجات الطلاب والفيديوهات</title>
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
                    <h2 class="mb-4">درجات الطلاب</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>اسم الطالب</th>
                                <th>الدرجة</th>
                                <th>المادة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
