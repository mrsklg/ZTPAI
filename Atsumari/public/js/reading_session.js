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
        coverImg.src = data.cover_url;
        pagesRead.textContent = data.totalPagesRead;
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

    // endSessionForm.addEventListener('submit', async (e) => {
    //     e.preventDefault();
    //     const userId = 'https://example.com/';
    //     const endDate = new Date().toISOString();
    //     const pagesRead = parseInt(document.getElementById('pageNumber').value, 10);
    //     const duration = elapsedTime / 1000;
    //
    //     const data = {
    //         user_id: userId,
    //         book_id: bookId,
    //         end_date: endDate,
    //         pages_read: pagesRead,
    //         duration: duration,
    //         start_date: new Date(Date.now() - elapsedTime).toISOString()
    //     };
    //
    //     try {
    //         const response = await fetch('/api/reading_session', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json'
    //             },
    //             body: JSON.stringify(data)
    //         });
    //
    //         if (response.ok) {
    //             window.location.href = "{{ path('current_book') }}";
    //         } else {
    //             console.error('Error saving session:', response.statusText);
    //         }
    //     } catch (error) {
    //         console.error('Error:', error);
    //     }
    // });
});