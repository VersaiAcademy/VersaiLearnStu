<?php
// Database connection settings
$host = "localhost";
$username = "u973762102_versaistudent";
$password = "Examversai@123";
$database = "u973762102_examversai"; // Make sure there is NO space or newline here

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
