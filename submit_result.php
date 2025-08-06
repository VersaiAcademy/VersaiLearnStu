<?php
include 'config.php'; // contains $conn

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $user_id = $data['user_id'];
    $score = $data['score'];
    $total_questions = $data['total_questions'];
    $correct_answers = $data['correct_answers'];
    $subject = $data['subject'];

    $stmt->bind_param("iiii", $user_id, $score, $total_questions, $correct_answers);

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
