import React, { useEffect, useState } from 'react';
import axios from 'axios';

const formatDate = (dateString) => {
    const dateObject = new Date(dateString);
    const year = dateObject.getFullYear();
    const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
    const day = dateObject.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const formatTime = (dateString) => {
    const dateObject = new Date(dateString);
    const hours = (dateObject.getHours() + 2).toString().padStart(2, '0');
    const minutes = dateObject.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
};

const CurrentBook = () => {
    const [book, setBook] = useState(null);
    const [bookStats, setBookStats] = useState(null);
    const [readingSessions, setReadingSessions] = useState([]);
    const [error, setError] = useState('');

    useEffect(() => {
        const fetchBookData = async () => {
            try {
                const bookResponse = await axios.get('http://127.0.0.1:8000/api/current_book_data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });

                const statsResponse = await axios.get('http://127.0.0.1:8000/api/user_book_stats', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });

                const sessionsResponse = await axios.get('http://127.0.0.1:8000/api/reading_sessions', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });

                setBook(bookResponse.data);
                setBookStats(statsResponse.data);
                setReadingSessions(sessionsResponse.data);
            } catch (error) {
                console.error('Error fetching data:', error.message);
                setError('Error fetching data');
            }
        };

        fetchBookData();
    }, []);

    if (error) {
        return (
            <main className="flex-row-center-center no-book">
                <h1 className="no-book">You are not reading any book</h1>
                <p className="no-book">Choose a book to read from <a href="/collection">collection</a>.</p>
            </main>
        );
    }

    if (!book || !bookStats) {
        return <div>Loading...</div>;
    }

    const remainingPages = Math.max(0, book.num_of_pages - bookStats.pagesReadCount);
    const remainingTime = Math.round(remainingPages / bookStats.readingSpeed);

    return (
        <main className="flex-column-space-around-center main-grid current-book-main">
            <h1>You are currently reading: {book.title}</h1>

            <div className="book-info-container flex-row-center-center">
                <div className="bookcover flex-row-center-center">
                    <img src={book.cover_url} alt="book cover" className="cover-img" />
                </div>

                <div className="reading-progess flex-column-space-around-center">
                    <h3>Your reading progress</h3>
                    <div className="bar">
                        <div className="progress-bar" style={{ width: `${Math.round((bookStats.pagesReadCount / book.num_of_pages) * 100)}%`, borderRadius: '2rem' }}></div>
                    </div>
                    <div className="progress-data flex-row-center-center">
                        <p className="progress-data-percentage">{Math.round((bookStats.pagesReadCount / book.num_of_pages) * 100)}%</p>
                        <p className="progress-data-numbers">{bookStats.pagesReadCount}/{book.num_of_pages}</p>
                    </div>
                    <a href={`/reading_session?id=${book.id}`} className="small-btn">Continue reading</a>
                </div>
            </div>

            <div className="reading-summary flex-column-space-around-center">
                <p>Your reading time: {Math.floor(bookStats.totalReadingTime / 3600)}h {Math.floor((bookStats.totalReadingTime % 3600) / 60)}min</p>
                <p>Your reading speed: {Math.round(bookStats.readingSpeed)} {Math.round(bookStats.readingSpeed) === 1 ? 'page' : 'pages'}/min</p>
                <p>Number of reading sessions: {bookStats.sessionsCount}</p>
                <p>Reading the same you’ll finish in: {Math.floor(remainingTime / 60)}h {remainingTime % 60}min</p>
            </div>

            <div className="history flex-column-space-around-center">
                <h4>History of reading sessions:</h4>
                {readingSessions.slice(0, 2).map(session => (
                    <div key={session.id} className="session-info flex-column-space-around-center">
                        <div className="flex-column-space-around-center session-details">
                            <p>{formatDate(session.startDate.date)}</p>
                            <p>{formatTime(session.startDate.date)} - {formatTime(session.endDate.date)}</p>
                        </div>
                        <div className="flex-column-space-around-center session-details">
                            <p>{Math.round(session.duration / 60)} minutes</p>
                            <p>{session.pagesRead} pages</p>
                        </div>
                    </div>
                ))}
                <button className="small-btn popup-btn" onClick={() => document.querySelector('.history-popup').classList.toggle('hide')}>See history</button>
            </div>

            <div className="history flex-column-space-around-center hide history-popup">
                <h4>History of reading sessions:</h4>
                {readingSessions.map(session => (
                    <div key={session.id} className="session-info flex-column-space-around-center">
                        <div className="flex-column-space-around-center session-details">
                            <p>{formatDate(session.startDate.date)}</p>
                            <p>{formatTime(session.startDate.date)} - {formatTime(session.endDate.date)}</p>
                        </div>
                        <div className="flex-column-space-around-center session-details">
                            <p>{Math.round(session.duration / 60)} minutes</p>
                            <p>{session.pagesRead} pages</p>
                        </div>
                    </div>
                ))}
                <button className="small-btn popup-btn" onClick={() => document.querySelector('.history-popup').classList.toggle('hide')}>Close</button>
            </div>
        </main>
    );
};

export default CurrentBook;
