<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
  

    $stmt = $conn->prepare("SELECT password, role FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_password, $user_role);
   

        if ( $stmt->fetch()) {
        echo "Login successful!";
        if ($user_role == 'student') {
            header("Location: materials.php");
        } elseif ($user_role == 'admin') {
            header("Location: admin/admin_page.php");
        } elseif ($user_role == 'teacher') {
            header("Location: teacher/teacher_page.php");
        } elseif ($user_role == 'parent') {
            header("Location: parent/parent_page.php");
        }
        
    } else {
        echo "Invalid email or password.";
        header("Location: login.php");
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>log_in</title>
</head>
<body >
  <form action="" method="post" dir="rtl">
    <h1>
      مرحباً
    </h1>
    <div class="mb-3">
      <label for="exampleInputEmail1" class="form-label"> البريد الالكترونى</label>
      <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
      <div id="emailHelp" class="form-text">لن نشارك بريدك الإلكتروني أبدًا مع أي شخص آخر.</div>
    </div>
    <div class="mb-3">
      <label for="exampleInputPassword1" class="form-label">كلمة السر</label>
      <input type="password" name="password" class="form-control" id="exampleInputPassword1">
    </div>
    <div>
      <p class="mb-3">
        ليس لديك حساب حتى الآن؟
        <a href="register.php">سجل الآن!</a>
      </p>
    </div>
    <div class="button">
          <button type="submit" class="btn btn-warning">تسجيل</button>
    </div>
  </form>
  <?php if (isset($message)): ?>
      <div class="alert alert-info" role="alert">
          <?php echo htmlspecialchars($message); ?>
      </div>
  <?php endif; ?>
  <div class="img">
    <img src="img/Saly-10.png" alt="">
  </div>
</body>
</html>