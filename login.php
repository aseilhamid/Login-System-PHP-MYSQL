<?php
session_start();
require "connection.php";

$error_msg = array('email' => '', 'password' => '');
$email = $password = '';
$status = true;

// validation
if (isset($_POST['submit'])) {


  //Recieve all data
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if (empty($email)) {
    $error_msg['email'] = 'email is required';
    $status = false;
  } else {
 
    $sql = "SELECT name, password FROM data WHERE email= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $param_email);
    $param_email = $email;
    if ($stmt->execute()) {
      $stmt->store_result();

      if ($stmt->num_rows() == 1) {
        $stmt->bind_result($name, $hashedpassword);
        $stmt->fetch();
        if (empty($password) || !(password_verify($password, $hashedpassword))) {
          $error_msg['password'] = 'incorrect password';
          $status = false;
        }
      } else {
        $error_msg['email'] = 'email is not recorded ';
        $status = false;
      }
    }


    if ($status) {
      $_SESSION['loggedin'] = true;
      header("Location: home.php?name=" . $name);
    }
  }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Login Form</title>
  <!-- Starlight CSS -->
  <link rel="stylesheet" href="assets/css/starlight.css">
</head>

<body>

  <div class="d-flex align-items-center justify-content-center bg-sl-primary ht-100v">

    <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white">
      <div class="signin-logo tx-center tx-24 tx-bold tx-inverse">Log<span class="tx-info tx-normal">in</span></div>
      <br>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <input type="email" name="email" class="form-control" placeholder="Enter your email">
          <span class="text-danger"><?php echo $error_msg['email']; ?></span>
        </div><!-- form-group -->

        <div class="form-group">
          <input type="password" name="password" class="form-control" placeholder="Enter your password">
          <span class="text-danger"><?php echo $error_msg['password']; ?></span>
        </div><!-- form-group -->

        <input type="submit" name="submit" class="btn btn-info btn-block" value="Login" />
      </form>

      <div class="mg-t-60 tx-center">Not yet a member? <a href="register.php" class="tx-info">Register</a></div>
    </div><!-- login-wrapper -->
  </div><!-- d-flex -->

  <script src="assets/lib/jquery/jquery.js"></script>
  <script src="assets/lib/popper.js/popper.js"></script>
  <script src="assets/lib/bootstrap/bootstrap.js"></script>

</body>

</html>