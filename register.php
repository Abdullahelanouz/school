<?php
// Include the database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO Users (username, phone, email, password) VALUES ('$username', '$phone', '$email', '$password')";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>Register</title>
</head>
<body>

  <form class="form" action="" method="post" dir="rtl">
    <h1>مرحباً</h1>
    <div class="mb-3">
      <label for="username" class="form-label"> الاسم بالكامل </label>
      <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
      <label for="phone" class="form-label">رقم الهاتف </label>
      <input type="text" class="form-control" id="phone" name="phone" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label"> البريد الالكترونى</label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">كلمة السر</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div>
      <p class="mb-3">
        هل لديك حساب حتى الآن؟  <a href="login.php">سجل الآن!</a>
      </p>
    </div>
    <div class="button">
      <button type="submit" class="btn btn-warning">تسجيل</button>
    </div>
  </form>
  <div class="img">
    <img src="img/Saly-10.png" alt="">
  </div>
</body>
</html>