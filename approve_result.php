<?php
include 'config.php'; // contains $conn

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['result_id'])) {
    $result_id = $data['result_id'];

    $stmt = $conn->prepare("UPDATE results SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $result_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "No result ID received"]);
}
?>
