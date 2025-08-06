<?php
include 'db.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT * FROM questions WHERE category = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Questions for: " . htmlspecialchars($category) . "</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p><strong>Q: " . $row['question'] . "</strong></p>";
        echo "<ul>";
        echo "<li>A: " . $row['option_a'] . "</li>";
        echo "<li>B: " . $row['option_b'] . "</li>";
        echo "<li>C: " . $row['option_c'] . "</li>";
        echo "<li>D: " . $row['option_d'] . "</li>";
        echo "</ul>";
        echo "</div><hr>";
    }
} else {
    echo "No questions found for this category.";
}
?>
