<?php
$conn = new mysqli("localhost", "u973762102_versaistudent", "Examversai@123", "u973762102_examversai");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = "Admin";
$email = "admin@example.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $email, $password, $role);

if ($stmt->execute()) {
    echo "✅ Admin added successfully!";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
