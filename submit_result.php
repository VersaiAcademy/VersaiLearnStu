<?php
session_start();
include 'config.php'; // contains $conn

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $score = $data['score'];
    $total_questions = $data['total_questions'];
    $correct_answers = $data['correct_answers'];
    $subject = $data['subject'];

    $stmt = $conn->prepare("INSERT INTO results (user_id, score, total_questions, correct_answers, subject, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("iiiis", $user_id, $score, $total_questions, $correct_answers, $subject);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "No data received"]);
}
?>
