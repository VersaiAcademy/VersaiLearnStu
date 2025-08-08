<?php
session_start();
$conn = new mysqli("localhost", "u973762102_versaistudent", "Examversai@123", "u973762102_examversai");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'student') {
                header("Location: subjects.html");
            } elseif ($user['role'] === 'admin') {
                header("Location: admin-dashboard.php");
            } else {
                echo "Unauthorized role.";
            }
            exit();
        } else {
            echo "Invalid Password";
        }
    } else {
        echo "User not found";
    }
}
?>
