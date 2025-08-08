<?php
// DB connection
$host = "localhost";
$username = "u973762102_versaistudent";
$password = "Examversai@123";
$database = "u973762102_examversai";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values
$fullname = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Always hash passwords

// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Email already exists. Please use another one.'); window.location.href='signup.html';</script>";
} else {
    // Proceed to insert
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $password);
    
    if ($stmt->execute()) {
        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$check->close();
$conn->close();
?>
