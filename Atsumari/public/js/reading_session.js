document.addEventListener('DOMContentLoaded', function () {
    let timer;
    let startTime;
    let elapsedTime = 0;
    const timerDisplay = document.getElementById('timer');
    const startBtn = document.getElementById('start-btn');
    const pauseBtn = document.getElementById('pause-btn');
    const stopBtn = document.getElementById('stop-btn');
    const endSessionFormContainer = document.getElementById('endSessionFormContainer');
    const endSessionForm = document.getElementById('endSessionForm');
    const cancelEndSession = document.getElementById('cancel-end-session');

    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('id');

    function updateTimer() {
        const time = Date.now() - startTime + elapsedTime;
        const hours = Math.floor(time / 3600000);
        const minutes = Math.floor((time % 3600000) / 60000);
        const seconds = Math.floor((time % 60000) / 1000);
        timerDisplay.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    const getCurrentBook = async () => {
        const coverImg = document.querySelector('.cover-img');
        const pagesRead = document.querySelector('#pages-read');
        const res = await fetch(`http://127.0.0.1:8000/api/current_book_data`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                'Content-Type': 'application/json'
            }
        });
        const data = await res.json();
        if (data.cover_url && data.pages_read_count) {
            coverImg.src = data.cover_url;
            pagesRead.textContent = data.pages_read_count;
        } else {
            const res = await fetch(`http://127.0.0.1:8000/api/books/${bookId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                    'Content-Type': 'application/json'
                }
            });
            const data = await res.json();
            coverImg.src = data.cover_url;
            pagesRead.textContent = 0;
        }

    }

    getCurrentBook();

    startBtn.addEventListener('click', () => {
        startTime = Date.now();
        timer = setInterval(updateTimer, 1000);
    });

    pauseBtn.addEventListener('click', () => {
        clearInterval(timer);
        elapsedTime += Date.now() - startTime;
    });

    stopBtn.addEventListener('click', () => {
        clearInterval(timer);
        elapsedTime += Date.now() - startTime;
        endSessionFormContainer.classList.toggle('blur');
        endSessionFormContainer.classList.toggle('hide');
        document.body.classList.toggle("body-no-blur");
    });

    cancelEndSession.addEventListener('click', () => {
        endSessionFormContainer.classList.toggle('blur');
        endSessionFormContainer.classList.toggle('hide');
        document.body.classList.toggle("body-no-blur");
    });

    endSessionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const endDate = new Date().toISOString();
        const pagesReadTotal = document.querySelector('#pages-read').textContent;
        const pagesRead = parseInt(document.getElementById('pageNumber').value, 10);
        const duration = Math.round(elapsedTime / 1000);

        if (pagesRead <= 0 || pagesRead <= pagesReadTotal) {
            alert(`Page number should be greater than number of pages read: ${pagesReadTotal}`);
            return;
        }

        if (duration < 60) {
            alert('The reading session must be at least one minute long.');
            return;
        }

        const startDate = new Date(Date.now() - elapsedTime).toISOString();

        const data = {
            end_date: endDate,
            pages_read: pagesRead - pagesReadTotal,
            duration: duration,
            start_date: startDate
        };

        try {
            const response = await fetch(`/api/reading_session?bookId=${bookId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/ld+json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                window.location.href = "/current_book";
            } else {
                console.error('Error saving session:', response.statusText);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});