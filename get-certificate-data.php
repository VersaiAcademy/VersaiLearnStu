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
$subject = isset($_GET['subject']) ? $_GET['subject'] : '';

if (empty($subject)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Subject not specified']);
    exit();
}

// Get user's name
$user_sql = "SELECT name FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$student_name = $user ? $user['name'] : 'Unknown Student';

// Get result details
$result_sql = "SELECT score, total_questions, submission_date FROM results WHERE user_id = ? AND subject = ? AND status = 'approved' ORDER BY submission_date DESC LIMIT 1";
$result_stmt = $conn->prepare($result_sql);
$result_stmt->bind_param("is", $user_id, $subject);
$result_stmt->execute();
$result_data = $result_stmt->get_result()->fetch_assoc();

if (!$result_data) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Approved result not found for this subject']);
    exit();
}

$percentage = ($result_data['score'] / $result_data['total_questions']) * 100;

$response = [
    'studentName' => $student_name,
    'subject' => $subject,
    'score' => $result_data['score'],
    'total' => $result_data['total_questions'],
    'percentage' => round($percentage, 2),
    'date' => date("F j, Y", strtotime($result_data['submission_date']))
];

header('Content-Type: application/json');
echo json_encode($response);
?>
