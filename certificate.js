window.addEventListener('load', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const studentName = urlParams.get('studentName');
    const examName = urlParams.get('examName');
    const status = urlParams.get('status');
    const score = urlParams.get('score');
    const total = urlParams.get('total');
    const percentage = urlParams.get('percentage');

    document.querySelector('.student-name').textContent = studentName;
    document.querySelector('.exam-name').textContent = examName;
    document.querySelector('.score').textContent = `${score}/${total}`;
    document.querySelector('.percentage').textContent = `${percentage}%`;
    const statusEl = document.querySelector('.status');
    statusEl.textContent = status;

    if (status.toLowerCase() === 'pass') {
        statusEl.classList.add('pass');
    } else {
        statusEl.classList.add('fail');
    }

    const downloadBtn = document.getElementById('download-btn');
    downloadBtn.addEventListener('click', () => {
        const certificate = document.querySelector('.certificate');
        downloadBtn.style.display = 'none';
        const opt = {
            margin:       0,
            filename:     'certificate.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
        };
        html2pdf().from(certificate).set(opt).save().then(() => {
            downloadBtn.style.display = 'block';
        });
    });
});
