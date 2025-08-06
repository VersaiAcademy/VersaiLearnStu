<?php
include 'config.php'; // contains $conn

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $user_id = $data['user_id'];
    $score = $data['score'];
    $total_questions = $data['total_questions'];
    $correct_answers = $data['correct_answers'];
    $subject = $data['subject'];

    $stmt = $conn->prepare("INSERT INTO results (user_id, score, total_questions, correct_answers, subject) VALUES (?, ?, ?, ?, ?)");
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
