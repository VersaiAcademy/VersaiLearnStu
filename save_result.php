<?php
session_start();

// Only accept JSON POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

// Get raw JSON POST body
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit();
}

require_once 'db_connection.php'; // <-- Replace with your DB file if named differently

$user_id = $_SESSION['user_id'] ?? $data['user_id'];
$score = $data['score'] ?? 0;
$total_questions = $data['total_questions'] ?? 0;
$correct_answers = $data['correct_answers'] ?? 0;
$subject = $data['subject'] ?? 'Unknown';

// Insert result into database
$sql = "INSERT INTO results (user_id, score, total_questions, correct_answers, subject) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed']);
    exit();
}
$stmt->bind_param("iiiis", $user_id, $score, $total_questions, $correct_answers, $subject);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'DB insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
