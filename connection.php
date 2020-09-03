<?php

// DB connection 
$server = "localhost";
$username = "root";
$password = "";
$db = "user";

$conn = new mysqli($server, $username, $password, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>