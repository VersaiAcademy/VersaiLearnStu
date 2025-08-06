<?php
session_start();

// Get user name and ID from session
$name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProExam - Student Exam</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <!-- Student Exam Interface -->
    <div id="student-interface">
        <!-- Header -->
        <header class="exam-header">
            <div class="container header-container">
                <div class="corner-logo">
                    <img src="images/versailogo.png" alt="Exam Logo">
                </div>
                <div class="exam-info">
                    <div class="info-item">
                        <span>Candidate:</span>
                        <span class="info-value" id="student-name">
                            <?php echo htmlspecialchars($name); ?> (ID: <?php echo htmlspecialchars($id); ?>)
                        </span>
                    </div>
                    <div class="info-item">
                        <span>Exam:</span>
                        <span class="info-value" id="exam-name"></span>
                    </div>
                    <div class="timer">
                        <i class="fas fa-clock"></i>
                        <span id="exam-timer">60:00</span>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main Content -->
        <main class="container">
            <div class="exam-container">
                <!-- Question Panel -->
                <div class="question-panel glass-card">
                    <div class="question-header">
                        <div class="question-number"></div>
                        <div class="question-meta">
                            <div class="question-meta-item">
                                <i class="fas fa-star"></i>
                                <span>1 Mark</span>
                            </div>
                           <div class="question-meta-item">
                              <i class="fas fa-clock"></i>
                              <span id="question-timer">00:45</span>
                           </div>
                        </div>
                    </div>

                    <div class="question-text"></div>
                    <div class="options-container"></div>

                    <div class="question-navigation">
                        <button class="nav-btn btn-outline" id="prev-btn">
                            <i class="fas fa-arrow-left" style="color: red;"></i> Previous
                        </button>
                        <button class="nav-btn btn-primary" id="next-btn">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Exam Sidebar -->
                <div class="exam-sidebar">
                    <div class="sidebar-card glass-card">
                        <h3 class="card-title">Question Palette</h3>
                        <div class="palette-grid" id="question-palette"></div>
                    </div>

                    <div class="sidebar-card glass-card">
                        <h3 class="card-title" style="color:white">Legend</h3>
                        <div class="legend-item">
                            <div class="palette-btn current" style="width: 20px; height: 20px; margin-right: 8px;"></div>
                            <span>Current</span>
                        </div>
                        <div class="legend-item">
                            <div class="palette-btn answered" style="width: 20px; height: 20px; margin-right: 8px;"></div>
                            <span>Answered</span>
                        </div>
                        <div class="legend-item">
                            <div class="palette-btn marked" style="width: 20px; height: 20px; margin-right: 8px;"></div>
                            <span>Marked</span>
                        </div>
                        <div class="legend-item">
                            <div class="palette-btn" style="width: 20px; height: 20px; margin-right: 8px;"></div>
                            <span>Unanswered</span>
                        </div>
                    </div>

                    <div class="sidebar-card glass-card">
                        <div class="exam-actions">
                            <button class="action-btn btn-mark" id="mark-btn">
                                <i class="fas fa-flag"></i> Mark for Review
                            </button>
                            <button class="action-btn btn-primary" id="clear-btn">
                                <i class="fas fa-eraser"></i> Clear Response
                            </button>
                            <button class="action-btn btn-submit" id="submit-btn">
                                <i class="fas fa-paper-plane"></i> Submit Exam
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Submit Confirmation Modal -->
        <div class="modal" id="submit-modal">
            <div class="modal-content glass-card">
                <div class="modal-header">
                    <h3 class="modal-title">Submit Examination</h3>
                </div>
                <div class="modal-body">
                    <p>You have attempted <strong id="attempted-questions">0 out of 0</strong> questions.</p>
                    <p>Are you sure you want to submit the exam?</p>
                    <div class="warning-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>You won't be able to change answers after submission.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="nav-btn btn-outline" id="cancel-submit">Cancel</button>
                    <button class="nav-btn btn-primary" id="confirm-submit">Submit Exam</button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const subject = urlParams.get("subject");

    const subjectFileMap = {
        "Graphic Design": "qgraphic-design.json",
        "Digital Marketing": "digitalmarketing.json",
        "Web Development": "web-development.json",
        "RS-CIT": "RS-CIT.json",
        "web_development.json": "web_development.json",
    };

    const jsonFile = subjectFileMap[subject];
    let questions = [];
    let currentQuestionIndex = 0;
    let answers = [];
    let examTimerInterval;
    let questionTimerInterval;

    if (!jsonFile) {
        alert("Invalid subject selected or subject not found.");
        return;
    }

    fetch(jsonFile)
        .then(res => res.json())
        .then(data => {
            questions = data.RS_CIT_Questions || data; // Handle both formats
            answers = Array(questions.length).fill(null);
            document.getElementById("exam-name").textContent = subject + " Certification";
            renderExam();
            renderPalette();
            startExamTimer(60 * 60); // 60 minutes
        })
        .catch(err => {
            console.error("Error loading JSON:", err);
            alert("Failed to load questions.");
        });

    function renderExam() {
        const q = questions[currentQuestionIndex];
        document.querySelector(".question-number").textContent = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
        document.querySelector(".question-text").textContent = q.question;

        const optionsContainer = document.querySelector(".options-container");
        optionsContainer.innerHTML = "";
        q.options.forEach((opt) => {
            const label = document.createElement("label");
            const isChecked = answers[currentQuestionIndex] === opt;
            label.innerHTML = `<input type="radio" name="option" value="${opt}" ${isChecked ? "checked" : ""}> ${opt}`;
            optionsContainer.appendChild(label);
        });

        updatePaletteHighlight();
        startQuestionTimer(45); // 45 seconds per question
    }

    function renderPalette() {
        const palette = document.getElementById("question-palette");
        palette.innerHTML = "";
        for (let i = 0; i < questions.length; i++) {
            const btn = document.createElement("button");
            btn.className = "palette-btn";
            btn.textContent = i + 1;
            btn.onclick = () => {
                saveAnswer();
                currentQuestionIndex = i;
                renderExam();
            };
            palette.appendChild(btn);
        }
    }

    function updatePaletteHighlight() {
        const buttons = document.querySelectorAll(".palette-btn");
        buttons.forEach((btn, i) => {
            btn.classList.remove("current", "answered", "marked");
            if (i === currentQuestionIndex) btn.classList.add("current");
            if (answers[i]) btn.classList.add("answered");
            // You can add logic for 'marked' if that state is stored somewhere
        });
    }

    function saveAnswer() {
        const selected = document.querySelector('input[name="option"]:checked');
        if (selected) {
            answers[currentQuestionIndex] = selected.value;
        }
    }

    function startExamTimer(duration) {
        let totalSeconds = duration;
        const timerDisplay = document.getElementById("exam-timer");
        examTimerInterval = setInterval(() => {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            if (--totalSeconds < 0) {
                clearInterval(examTimerInterval);
                alert("Time's up! Auto-submitting your exam.");
                submitExam();
            }
        }, 1000);
    }

    function startQuestionTimer(duration) {
        if (questionTimerInterval) clearInterval(questionTimerInterval);
        let timeLeft = duration;
        const timerElement = document.getElementById("question-timer");
        questionTimerInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            if (--timeLeft < 0) {
                clearInterval(questionTimerInterval);
                // alert("Time up for this question!");
                // Optionally move to the next question automatically
                if (currentQuestionIndex < questions.length - 1) {
                    document.getElementById("next-btn").click();
                } else {
                    // Last question, maybe auto-submit
                }
            }
        }, 1000);
    }

    function submitExam() {
        saveAnswer();
        const correctAnswers = answers.filter((ans, i) => ans === questions[i].answer).length;
        const totalQuestions = questions.length;

        const resultData = {
            user_id: <?php echo json_encode($id); ?>,
            score: correctAnswers,
            total_questions: totalQuestions,
            correct_answers: correctAnswers,
            subject: subject
        };

        fetch('submit_result.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(resultData)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                alert("✅ Exam Submitted & Result Saved!");
                window.location.href = "subjects.html";
            } else {
                alert("❌ Error: " + res.error);
            }
        })
        .catch(err => {
            console.error("Fetch Error:", err);
            alert("❌ Failed to submit exam.");
        });
    }

    document.getElementById("next-btn").onclick = () => {
        saveAnswer();
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            renderExam();
        }
    };

    document.getElementById("prev-btn").onclick = () => {
        saveAnswer();
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            renderExam();
        }
    };

    document.getElementById("clear-btn").onclick = () => {
        answers[currentQuestionIndex] = null;
        renderExam();
    };

    document.getElementById("mark-btn").onclick = () => {
        document.querySelectorAll(".palette-btn")[currentQuestionIndex].classList.toggle("marked");
    };

    document.getElementById("submit-btn").onclick = () => {
        const attempted = answers.filter(a => a !== null).length;
        document.getElementById("attempted-questions").textContent = `${attempted} out of ${questions.length}`;
        document.getElementById("submit-modal").style.display = "flex";
    };

    document.getElementById("cancel-submit").onclick = () => {
        document.getElementById("submit-modal").style.display = "none";
    };

    document.getElementById("confirm-submit").onclick = submitExam;
});
</script>

</body>
</html>
