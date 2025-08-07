<?php
session_start();
include 'config.php'; // contains $conn

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Debug line: check session value
// echo "Logged in User ID: " . $user_id;

$user_sql = "SELECT name FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$student_name = $user ? $user['name'] : 'Student';

// Get results for the logged-in user
$sql = "SELECT subject, score, total_questions, status, submission_date FROM results WHERE user_id = ? ORDER BY submission_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Store all rows to avoid re-fetching issues
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results - ProExam</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1>Welcome, <?= htmlspecialchars($student_name); ?>!</h1>
            <p>Here are your exam results.</p>
            <a href="subjects.html" class="btn btn-primary">Back to Subjects</a>
        </header>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="results-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Certificate</th>
                            </tr>
                        </thead>
                      <tbody>
<?php if (count($rows) > 0): ?>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($student_name); ?></td>
            <td><?= htmlspecialchars($row['subject']); ?></td>
            <td>
                <?php if ($row['status'] == 'approved'): ?>
                    <?= $row['score']; ?> / <?= $row['total_questions']; ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['status']); ?></td>
            <td><?= htmlspecialchars($row['submission_date']); ?></td>
            <td>
                <?php if ($row['status'] == 'approved'): ?>
                    <a href="certificate.html?subject=<?= urlencode($row['subject']); ?>&date=<?= urlencode($row['submission_date']); ?>" class="btn btn-success">Download</a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="6">You have not taken any exams yet.</td></tr>
<?php endif; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
