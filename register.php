<?php
session_start();
     
$status = true;
$error_msg = array('name' => '', 'email' => '', 'password' => '', 'phone' => '', 'address' => '');
$name = $email = $password = $phone = $address = $hashpassword = "";

// connection
require "connection.php";

// validation
if (isset($_POST['submit'])) {

  //Recieve all data
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);

  //validate data
  if (empty($name)) {
    $error_msg['name'] = 'name is required';
    $status = false;
  }

  if (empty($password)) {
    $error_msg['password'] = 'password is required';
    $status = false;
  } else if (strlen($password) < 8) {
    $error_msg['password'] = 'password is less than 8 chars';
    $status = false;
  } else {
    $hashpassword = password_hash($password, PASSWORD_DEFAULT);
  }

  if (empty($phone)) {
    $error_msg['phone'] = 'phone is required';
    $status = false;
  }

  if (empty($address)) {
    $error_msg['address'] = 'address is required';
    $status = false;
  }

  if (empty($email)) {
    $error_msg['email'] = 'email is required';
    $status = false;
  }
    //check if the email already exist
    $sql = "SELECT name, password FROM data WHERE email= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $param_email);
    $param_email = $email;
    if ($stmt->execute()) {
      $stmt->store_result();

    if ($stmt->num_rows() > 0){
      $error_msg['email'] = 'email already exsit';
      $status = false;
      $stmt->close();
    }
  }


  // Insert into DB
  if ($status) {
    $sql = "INSERT INTO data (name, email, password, phone, address) 
                  VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",$name, $email, $hashpassword, $phone, $address);
    if($stmt->execute()){
      $_SESSION['loggedin'] = true;

      echo "<script>";
      echo " alert(('Registeration successfully Done.'));";
      echo 'window.location.href="home.php?name= '.$name .'";    
              </script>';

      $stmt->close();
    }else {
      echo "Connection error ";
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

  <title>Registration Form</title>

  <!-- Starlight CSS -->
  <link rel="stylesheet" href="assets/css/starlight.css">
</head>

<body>

  <div class="d-flex align-items-center justify-content-center bg-sl-primary ht-md-100v">

    <div class="login-wrapper wd-300 wd-xs-400 pd-25 pd-xs-40 bg-white">
      <div class="signin-logo tx-center tx-24 tx-bold tx-inverse">Register<span class="tx-info tx-normal">ation</span></div>
      <br>

      <form method="POST"  enctype="multipart/form-data">
        <div class="form-group">
          <input type="text" name="name" class="form-control" placeholder="Enter your username">
          <span class="text-danger"><?php echo $error_msg['name']; ?></span>
        </div><!-- form-group -->

        <div class="form-group">
          <input type="email" name="email" class="form-control" placeholder="Enter your email">
          <span class="text-danger"><?php echo $error_msg['email']; ?></span>
        </div><!-- form-group -->

        <div class="form-group">
          <input type="password" name="password" class="form-control" placeholder="Enter your password">
          <span class="text-danger"><?php echo $error_msg['password']; ?></span>
        </div><!-- form-group -->

        <div class="form-group">
          <input type="tel" name="phone" maxlength="11" class="form-control" placeholder="01xxxxxxxxx" pattern="[0-9]{11}">
          <span class="text-danger"><?php echo $error_msg['phone']; ?></span>
        </div><!-- form-group -->

        <div class="form-group">
          <input type="text" name="address" class="form-control" placeholder="Enter you Address">
          <span class="text-danger"><?php echo $error_msg['address']; ?></span>
        </div><!-- form-group -->

        <input type="submit" name="submit" class="btn btn-info btn-block" value="Register" />
      </form>

      <div class="mg-t-40 tx-center">Already have an account? <a href="login.php" class="tx-info">Login</a></div>
    </div><!-- login-wrapper -->
  </div><!-- d-flex -->

  <script src="assets/lib/jquery/jquery.js"></script>
  <script src="assets/lib/popper.js/popper.js"></script>
  <script src="assets/lib/bootstrap/bootstrap.js"></script>

  

</body>

</html>