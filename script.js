document.getElementById("confirm-submit").addEventListener("click", function () {
    // Example values, replace with real values
    const userId = 1; // dynamically from session or hidden field
    const score = 8;
    const totalQuestions = 10;
    const correctAnswers = 8;

    fetch("submit_result.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            user_id: userId,
            score: score,
            total_questions: totalQuestions,
            correct_answers: correctAnswers
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Should show { success: true } or similar
        alert("Result submitted successfully!");
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Failed to submit result.");
    });
});
