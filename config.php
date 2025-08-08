<?php
$host = "localhost";
$username = "u973762102_versaistudent";
$password = "Examversai@123";
$database = "u973762102_examversai";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
