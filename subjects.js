document.addEventListener('DOMContentLoaded', () => {
    // This will run after script.js has loaded and initialized appState
    auth.onAuthStateChanged(user => {
        if (user) {
            disableCompletedExams();
        }
    });
});

function disableCompletedExams() {
    if (!appState.currentUser || !appState.examData.results) {
        console.log("User data or results not ready yet.");
        return;
    }

    const studentId = appState.currentUser.id;
    const completedExams = appState.examData.results
        .filter(result => result.studentId === studentId)
        .map(result => {
            const exam = appState.examData.exams.find(e => e.id === result.examId);
            // Extract subject from "Subject Certification"
            return exam ? exam.name.replace(' Certification', '') : null;
        })
        .filter(Boolean);

    const subjectDivs = document.querySelectorAll('.subject');
    subjectDivs.forEach(div => {
        const subjectName = div.querySelector('h2').textContent;
        if (completedExams.includes(subjectName)) {
            div.classList.add('completed');
            div.onclick = () => {
                alert('You have already completed the exam for this subject.');
                // Optionally, redirect to the result page
                localStorage.setItem('selectedSubject', subjectName);
                window.location.href = 'result.html';
            };
        }
    });
}


function selectSubject(subject) {
    // The logic to prevent re-taking is now handled in disableCompletedExams,
    // but we keep this function for subjects that are not completed.
    localStorage.setItem('selectedSubject', subject);
    window.location.href = 'student-exam.html';
}
