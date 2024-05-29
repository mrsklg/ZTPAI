// function formatDate(dateString) {
//     const dateObject = new Date(dateString);
//
//     const year = dateObject.getFullYear();
//     const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
//     const day = dateObject.getDate().toString().padStart(2, '0');
//
//     return `${year}-${month}-${day}`;
// }
//
// function formatTime(dateString) {
//     const dateObject = new Date(dateString);
//
//     const hours = (dateObject.getHours() + 2).toString().padStart(2, '0');
//     const minutes = dateObject.getMinutes().toString().padStart(2, '0');
//
//     return `${hours}:${minutes}`;
// }
//
// const getCurrentBook = async () => {
//     const res = await fetch(`http://127.0.0.1:8000/api/current_book_data`, {
//         method: 'GET',
//         headers: {
//             'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
//             'Content-Type': 'application/json'
//         }
//     });
//     const data = await res.json();
//
//     const resStats = await fetch(`http://127.0.0.1:8000/api/user_book_stats`, {
//         method: 'GET',
//         headers: {
//             'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
//             'Content-Type': 'application/json'
//         }
//     });
//     const dataStats = await resStats.json();
//
//     document.querySelector('h1').textContent = `You are currently reading: ${data['title']}`
//     document.querySelector('.cover-img').src = data['cover_url'];
//     document.querySelector('.progress-bar').style.width = `${Math.round((dataStats['pagesReadCount']/data['num_of_pages']) * 100)}%`;
//     document.querySelector('.progress-data-percentage').textContent = `${Math.round((dataStats['pagesReadCount']/data['num_of_pages']) * 100)}%`;
//     document.querySelector('.progress-data-numbers').textContent = `${dataStats['pagesReadCount']}/${data['num_of_pages']}`;
//     document.querySelector('.reading-progess > a').href = `/reading_session?id=${data['id']}`;
//
//     const readingSpeed = Math.round(dataStats["readingSpeed"]);
//     document.querySelector('.reading-time').textContent = `${Math.floor(dataStats["totalReadingTime"] / 3600)}h ${Math.floor((dataStats["totalReadingTime"] % 3600) / 60)}min`
//     document.querySelector('.reading-speed').textContent = `${readingSpeed} ${readingSpeed === 1 ? 'page' : 'pages'}/min`;
//     document.querySelector('.sessions-count').textContent = dataStats["sessionsCount"];
//
//     const remainingPages = Math.max(0, data['num_of_pages'] - dataStats['pagesReadCount']);
//     const remainingTime = Math.round(remainingPages / dataStats["readingSpeed"]);
//     document.querySelector('.remaining-time').textContent = `${Math.floor(remainingTime / 60)}h ${remainingTime % 60}min`
// }
//
// const getReadingSessions = async () => {
//     const res = await fetch(`http://127.0.0.1:8000/api/reading_sessions`, {
//         method: 'GET',
//         headers: {
//             'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
//             'Content-Type': 'application/json'
//         }
//     });
//     const reading_sessions = await res.json();
//
//     document.querySelector('.history').innerHTML = '<h4>History of reading sessions:</h4>'
//
//     for (let i = 0; i < 2; i++) {
//         const sessionDiv = document.createElement('div');
//         sessionDiv.classList.add('session-info', 'flex-column-space-around-center');
//
//         const sessionDetailsLeft = document.createElement('div');
//         sessionDetailsLeft.classList.add('flex-column-space-around-center', 'session-details');
//
//         const sessionDetailsRight = document.createElement('div');
//         sessionDetailsRight.classList.add('flex-column-space-around-center', 'session-details');
//
//         const startDateParagraph = document.createElement('p');
//         startDateParagraph.textContent = formatDate(reading_sessions[i].startDate.date);
//         sessionDetailsLeft.appendChild(startDateParagraph);
//
//         const timeRangeParagraph = document.createElement('p');
//         timeRangeParagraph.textContent = `${formatTime(reading_sessions[i].startDate.date)} - ${formatTime(reading_sessions[i].endDate.date)}`;
//         sessionDetailsLeft.appendChild(timeRangeParagraph);
//
//         const durationParagraph = document.createElement('p');
//         durationParagraph.textContent = `${Math.round(reading_sessions[i].duration / 60)} minutes`;
//         sessionDetailsRight.appendChild(durationParagraph);
//
//         const pagesReadParagraph = document.createElement('p');
//         pagesReadParagraph.textContent = `${reading_sessions[i].pagesRead} pages`;
//         sessionDetailsRight.appendChild(pagesReadParagraph);
//
//         sessionDiv.appendChild(sessionDetailsLeft);
//         sessionDiv.appendChild(sessionDetailsRight);
//
//         document.querySelector('.history').appendChild(sessionDiv);
//     }
//
//     document.querySelector('.history-popup').innerHTML = '<h4>History of reading sessions:</h4>'
//
//     reading_sessions.forEach(session => {
//         const sessionDiv = document.createElement('div');
//         sessionDiv.classList.add('session-info', 'flex-column-space-around-center');
//
//         const sessionDetailsLeft = document.createElement('div');
//         sessionDetailsLeft.classList.add('flex-column-space-around-center', 'session-details');
//
//         const sessionDetailsRight = document.createElement('div');
//         sessionDetailsRight.classList.add('flex-column-space-around-center', 'session-details');
//
//         const startDateParagraph = document.createElement('p');
//         startDateParagraph.textContent = formatDate(session.startDate.date);
//         sessionDetailsLeft.appendChild(startDateParagraph);
//
//         const timeRangeParagraph = document.createElement('p');
//         timeRangeParagraph.textContent = `${formatTime(session.startDate.date)} - ${formatTime(session.endDate.date)}`;
//         sessionDetailsLeft.appendChild(timeRangeParagraph);
//
//         const durationParagraph = document.createElement('p');
//         durationParagraph.textContent = `${Math.round(session.duration / 60)} minutes`;
//         sessionDetailsRight.appendChild(durationParagraph);
//
//         const pagesReadParagraph = document.createElement('p');
//         pagesReadParagraph.textContent = `${session.pagesRead} pages`;
//         sessionDetailsRight.appendChild(pagesReadParagraph);
//
//         sessionDiv.appendChild(sessionDetailsLeft);
//         sessionDiv.appendChild(sessionDetailsRight);
//
//         document.querySelector('.history-popup').appendChild(sessionDiv);
//     });
//
//     document.querySelector('.history').innerHTML += '<button class="small-btn popup-btn">See history</button>'
//     document.querySelector('.history-popup').innerHTML += '<button class="small-btn popup-btn">Close</button>'
//
//     document.addEventListener('click', function(event) {
//         if (event.target.classList.contains('popup-btn')) {
//             document.querySelector('.history-popup').classList.toggle('hide');
//         }
//     });
// }
//
// getCurrentBook()
// getReadingSessions()
//


function formatDate(dateString) {
    const dateObject = new Date(dateString);

    const year = dateObject.getFullYear();
    const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
    const day = dateObject.getDate().toString().padStart(2, '0');

    return `${year}-${month}-${day}`;
}

function formatTime(dateString) {
    const dateObject = new Date(dateString);

    const hours = (dateObject.getHours() + 2).toString().padStart(2, '0');
    const minutes = dateObject.getMinutes().toString().padStart(2, '0');

    return `${hours}:${minutes}`;
}

const createElement = (tag, classNames, textContent) => {
    const element = document.createElement(tag);
    if (classNames) element.className = classNames;
    if (textContent) element.textContent = textContent;
    return element;
}

const getCurrentBook = async () => {
    const res = await fetch(`http://127.0.0.1:8000/api/current_book_data`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'application/json'
        }
    });
    const data = await res.json();

    const resStats = await fetch(`http://127.0.0.1:8000/api/user_book_stats`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'application/json'
        }
    });
    const dataStats = await resStats.json();

    document.querySelector('main').remove();

    const mainContent = createElement('main', 'flex-column-space-around-center main-grid');

    const title = createElement('h1', null, `You are currently reading: ${data.title}`);
    mainContent.appendChild(title);

    const bookInfoContainer = createElement('div', 'book-info-container flex-row-center-center');

    const bookCover = createElement('div', 'bookcover flex-row-center-center');
    const coverImg = createElement('img', 'cover-img');
    coverImg.src = data.cover_url;
    coverImg.alt = 'bookcover';
    bookCover.appendChild(coverImg);

    const readingProgress = createElement('div', 'reading-progess flex-column-space-around-center');
    const progressTitle = createElement('h3', null, 'Your reading progress');
    readingProgress.appendChild(progressTitle);

    const bar = createElement('div', 'bar');
    const progressBar = createElement('div', 'progress-bar');
    progressBar.style.width = `${Math.round((dataStats.pagesReadCount / data.num_of_pages) * 100)}%`;
    progressBar.style.borderRadius = '2rem';
    bar.appendChild(progressBar);
    readingProgress.appendChild(bar);

    const progressData = createElement('div', 'progress-data flex-row-center-center');
    const progressPercentage = createElement('p', 'progress-data-percentage', `${Math.round((dataStats.pagesReadCount / data.num_of_pages) * 100)}%`);
    const progressNumbers = createElement('p', 'progress-data-numbers', `${dataStats.pagesReadCount}/${data.num_of_pages}`);
    progressData.appendChild(progressPercentage);
    progressData.appendChild(progressNumbers);
    readingProgress.appendChild(progressData);

    const continueReading = createElement('a', 'small-btn', 'Continue reading');
    continueReading.href = `/reading_session?id=${data.id}`;
    readingProgress.appendChild(continueReading);

    bookInfoContainer.appendChild(bookCover);
    bookInfoContainer.appendChild(readingProgress);
    mainContent.appendChild(bookInfoContainer);

    const readingSummary = createElement('div', 'reading-summary flex-column-space-around-center');
    const readingTime = createElement('p', null, `Your reading time: ${Math.floor(dataStats.totalReadingTime / 3600)}h ${Math.floor((dataStats.totalReadingTime % 3600) / 60)}min`);
    const readingSpeed = createElement('p', null, `Your reading speed: ${Math.round(dataStats.readingSpeed)} ${Math.round(dataStats.readingSpeed) === 1 ? 'page' : 'pages'}/min`);
    const sessionsCount = createElement('p', null, `Number of reading sessions: ${dataStats.sessionsCount}`);
    const remainingPages = Math.max(0, data.num_of_pages - dataStats.pagesReadCount);
    const remainingTime = Math.round(remainingPages / dataStats.readingSpeed);
    const remainingTimeText = createElement('p', null, `Reading the same you’ll finish in: ${Math.floor(remainingTime / 60)}h ${remainingTime % 60}min`);

    readingSummary.appendChild(readingTime);
    readingSummary.appendChild(readingSpeed);
    readingSummary.appendChild(sessionsCount);
    readingSummary.appendChild(remainingTimeText);

    mainContent.appendChild(readingSummary);

    const history = createElement('div', 'history flex-column-space-around-center');
    const historyTitle = createElement('h4', null, 'History of reading sessions:');
    const historyButton = createElement('button', 'small-btn', 'See history');
    historyButton.classList.add('popup-btn');
    history.appendChild(historyTitle);

    mainContent.appendChild(history);

    const historyPopup = createElement('div', 'history flex-column-space-around-center hide history-popup');
    const historyPopupTitle = createElement('h4', null, 'History of reading sessions:');
    const closeHistoryButton = createElement('button', 'small-btn', 'Close');
    closeHistoryButton.classList.add('popup-btn');
    historyPopup.appendChild(historyPopupTitle);

    mainContent.appendChild(historyPopup);

    document.body.appendChild(mainContent);

    const resSessions = await fetch(`http://127.0.0.1:8000/api/reading_sessions`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'application/json'
        }
    });
    const reading_sessions = await resSessions.json();

    const historyContainer = document.querySelector('.history');

    reading_sessions.slice(0, 2).forEach(session => {
        const sessionDiv = createElement('div', 'session-info flex-column-space-around-center');

        const sessionDetailsLeft = createElement('div', 'flex-column-space-around-center session-details');
        const startDateParagraph = createElement('p', null, formatDate(session.startDate.date));
        const timeRangeParagraph = createElement('p', null, `${formatTime(session.startDate.date)} - ${formatTime(session.endDate.date)}`);
        sessionDetailsLeft.appendChild(startDateParagraph);
        sessionDetailsLeft.appendChild(timeRangeParagraph);

        const sessionDetailsRight = createElement('div', 'flex-column-space-around-center session-details');
        const durationParagraph = createElement('p', null, `${Math.round(session.duration / 60)} minutes`);
        const pagesReadParagraph = createElement('p', null, `${session.pagesRead} pages`);
        sessionDetailsRight.appendChild(durationParagraph);
        sessionDetailsRight.appendChild(pagesReadParagraph);

        sessionDiv.appendChild(sessionDetailsLeft);
        sessionDiv.appendChild(sessionDetailsRight);

        historyContainer.appendChild(sessionDiv);
    });

    reading_sessions.forEach(session => {
        const sessionDiv = createElement('div', 'session-info flex-column-space-around-center');

        const sessionDetailsLeft = createElement('div', 'flex-column-space-around-center session-details');
        const startDateParagraph = createElement('p', null, formatDate(session.startDate.date));
        const timeRangeParagraph = createElement('p', null, `${formatTime(session.startDate.date)} - ${formatTime(session.endDate.date)}`);
        sessionDetailsLeft.appendChild(startDateParagraph);
        sessionDetailsLeft.appendChild(timeRangeParagraph);

        const sessionDetailsRight = createElement('div', 'flex-column-space-around-center session-details');
        const durationParagraph = createElement('p', null, `${Math.round(session.duration / 60)} minutes`);
        const pagesReadParagraph = createElement('p', null, `${session.pagesRead} pages`);
        sessionDetailsRight.appendChild(durationParagraph);
        sessionDetailsRight.appendChild(pagesReadParagraph);

        sessionDiv.appendChild(sessionDetailsLeft);
        sessionDiv.appendChild(sessionDetailsRight);

        historyPopup.appendChild(sessionDiv);
    });
    history.appendChild(historyButton);
    historyPopup.appendChild(closeHistoryButton);


    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('popup-btn')) {
            document.querySelector('.history-popup').classList.toggle('hide');
        }
    });
}

const getReadingSessions = async () => {

}

getCurrentBook();
getReadingSessions();
