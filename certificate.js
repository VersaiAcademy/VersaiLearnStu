document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const subject = urlParams.get('subject');

    if (subject) {
        fetch(`get-certificate-data.php?subject=${encodeURIComponent(subject)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.body.innerHTML = `<div class="container"><p>Error: ${data.error}</p></div>`;
                    return;
                }

                document.querySelector('.student-name').textContent = data.studentName;
                document.querySelector('.subject-name').textContent = data.subject;
                document.querySelector('.score').textContent = data.score;
                document.querySelector('.percentage').textContent = data.percentage;
                document.querySelector('.award-date').textContent = data.date;

                const downloadBtn = document.getElementById('download-btn');
                downloadBtn.addEventListener('click', () => {
                    const certificate = document.querySelector('.certificate');
                    const opt = {
                        margin: 0,
                        filename: `${data.studentName}_${data.subject}_certificate.pdf`,
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
                    };
                    html2pdf().from(certificate).set(opt).save();
                });
            })
            .catch(error => {
                console.error('Error fetching certificate data:', error);
                document.body.innerHTML = `<div class="container"><p>Could not load certificate data.</p></div>`;
            });
    } else {
        document.body.innerHTML = `<div class="container"><p>No subject specified.</p></div>`;
    }
});
