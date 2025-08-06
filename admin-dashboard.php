<?php
// Start session and DB connection
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || trim(strtolower($_SESSION['role'])) !== 'admin') {
    // Redirect to login page if not authorized
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "proexam");

// Check DB connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get all results from DB
$sql = "SELECT r.user_id, u.name, r.score, r.total_questions, r.subject, r.submit_time FROM results r JOIN users u ON r.user_id = u.id ORDER BY r.submit_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProExam - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
      <link rel="icon" type="image/png" href="images/favicon.svg">
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="images/versailogo.png" alt="Admin Logo" class="sidebar-logo"
                     style="width: 100%; height: auto; max-width: 150px; margin: 0 auto;">
            </div>
            <nav class="sidebar-nav">
                <button class="nav-btn active" data-tab="results"><i class="fas fa-poll"></i><span>Exam Results</span></button>
                <button class="nav-btn" data-tab="students"><i class="fas fa-user-graduate"></i><span>Students</span></button>
                <button class="nav-btn" data-tab="logout"><i class="fas fa-sign-out-alt"></i><a href="login.html" target="_blank" rel="noopener noreferrer"><span>Logout</span></a></button>
            </nav>
        </aside>

        <main class="main-content">
            <header class="main-header"><h1 id="main-title">Exam Results</h1></header>

            <div class="tab-content active" id="results-tab">
                <div class="card">
                    <div class="card-header"><h3>Recent Exam Submissions</h3></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Score</th>
                                        <th>Status</th>
                                        <th>Submit Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['user_id']; ?></td>
                                                <td><?= htmlspecialchars($row['name']); ?></td>
                                                <td><?= htmlspecialchars($row['subject']); ?></td>
                                                <td><?= $row['score']; ?> / <?= $row['total_questions']; ?></td>
                                                <td><?= $row['score'] >= ($row['total_questions'] / 2) ? 'Pass' : 'Fail'; ?></td>
                                                <td><?= $row['submit_time']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7">No results found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs: Students & Settings (unchanged) -->
            <div class="tab-content" id="students-tab">
                <div class="card">
                    <div class="card-header"><h3>Registered Students</h3></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Exams Taken</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- You can fetch and list students here later -->
                                    <tr><td colspan="5">Coming soon...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="settings-tab">
                <div class="card">
                    <div class="card-header"><h3>System Settings</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Admin Access Code</label>
                            <div class="input-group">
                                <input type="text" class="form-input" id="admin-access-code" value="123456" readonly>
                                <button class="btn btn-secondary" id="generate-code-btn">
                                    <i class="fas fa-sync-alt"></i> Generate New
                                </button>
                            </div>
                            <p class="form-help">This code is required for new admin registrations.</p>
                        </div>
                    </div>
                    <div class="sidebar-footer">
                        <button class="logout-btn" id="admin-logout" style="width:200px;height: 50px;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
