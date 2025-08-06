<?php
include 'config.php';

$name = 'Admin';
$email = 'admin2@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);

// Check if admin email already exists
$checkQuery = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Admin user already exists.";
} else {
    $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        echo "Admin user created successfully.";
    } else {
        echo "Error creating admin: " . $stmt->error;
    }
}
?>
