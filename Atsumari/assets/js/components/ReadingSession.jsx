import React, { useState, useEffect } from 'react';
import axios from 'axios';

const ReadingSession = () => {
    // const [timer, setTimer] = useState(null);
    // const [startTime, setStartTime] = useState(null);
    // const [elapsedTime, setElapsedTime] = useState(0);
    const [pagesRead, setPagesRead] = useState(0);
    const [coverUrl, setCoverUrl] = useState('');
    const [formVisible, setFormVisible] = useState(false);
    const [pageNumber, setPageNumber] = useState('');
    const bookId = new URLSearchParams(window.location.search).get('id');


    const [start, setStart] = useState(false);
    const [count, setCount] = useState(0);
    const [time, setTime] = useState("00:00:00");
    const [timeSetting, setTimeSetting] = useState({ m: 0, s: 0 });

    let initTime = new Date();

    const showTimer = (ms) => {
        const milliseconds = Math.floor((ms % 1000) / 10)
            .toString()
            .padStart(2, "0");
        const second = Math.floor((ms / 1000) % 60)
            .toString()
            .padStart(2, "0");
        const minute = Math.floor((ms / 1000 / 60) % 60)
            .toString()
            .padStart(2, "0");
        const hour = Math.floor(ms / 1000 / 60 / 60).toString();
        setTime(
            hour.padStart(2, "0") +
            ":" +
            minute + ":" + second //+ ":" + milliseconds
        );
    };

    const stopSession = () => {
        setFormVisible(true);
        console.log(time, count)
    };

    useEffect(() => {
        if (!start) {
            return;
        }
        var id = setInterval(() => {
            var left = count + (new Date() - initTime);
            setCount(left);
            showTimer(left);
            if (left <= 0) {
                setTime("00:00:00:00");
                clearInterval(id);
            }
        }, 1);
        return () => clearInterval(id);
    }, [start]);



    useEffect(() => {
        const fetchCurrentBook = async () => {
            try {
                const res = await axios.get(`http://127.0.0.1:8000/api/current_book_data`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                    }
                });
                if (res.data.cover_url && res.data.pages_read_count) {
                    setCoverUrl(res.data.cover_url);
                    setPagesRead(res.data.pages_read_count);
                } else {
                    const resBook = await axios.get(`http://127.0.0.1:8000/api/books/${bookId}`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                        }
                    });
                    setCoverUrl(resBook.data.cover_url);
                    setPagesRead(0);
                }
            } catch (error) {
                console.error('Error fetching book data:', error);
            }
        };

        fetchCurrentBook();
    }, [bookId]);

    // const updateTimer = () => {
    //     const time = Date.now() - startTime + elapsedTime;
    //     const hours = Math.floor(time / 3600000);
    //     const minutes = Math.floor((time % 3600000) / 60000);
    //     const seconds = Math.floor((time % 60000) / 1000);
    //     return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    // };

    // const updateTimer = () => {
    //     if (startTime === null) {
    //         return '00:00:00';
    //     }
    //
    //     const time = Date.now() - startTime + elapsedTime;
    //     const hours = Math.floor(time / 3600000);
    //     const minutes = Math.floor((time % 3600000) / 60000);
    //     const seconds = Math.floor((time % 60000) / 1000);
    //     return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    // };

    // const handleStart = () => {
    //     setStartTime(Date.now());
    //     setTimer(setInterval(() => setElapsedTime(prevTime => prevTime + 1000), 1000));
    // };

    // const handleStart = () => {
    //     if (!timer) {
    //         setStartTime(Date.now());
    //         const newTimer = setInterval(() => setElapsedTime(prevTime => prevTime + 1000), 1000);
    //         setTimer(newTimer);
    //     }
    // };

    // const handlePause = () => {
    //     clearInterval(timer);
    //     console.log(elapsedTime)
    //     setElapsedTime(elapsedTime + (Date.now() - startTime));
    // };

    // const handleStop = () => {
    //     clearInterval(timer);
    //     console.log(elapsedTime)
    //     setElapsedTime(elapsedTime + (Date.now() - startTime));
    //     setFormVisible(true);
    // };

    const handleCancel = () => {
        setFormVisible(false);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const duration = Math.round(count / 1000);
        if (duration < 60) {
            alert('The reading session must be at least one minute long.');
            return;
        }
        if (pageNumber <= pagesRead) {
            alert(`Page number should be greater than number of pages read: ${pagesRead}`);
            return;
        }
        const data = {
            end_date: new Date().toISOString(),
            pages_read: pageNumber - pagesRead,
            duration: duration,
            start_date: new Date(Date.now() - count).toISOString()
        };

        try {
            const response = await axios.post(`/api/reading_session?bookId=${bookId}`, data, {
                headers: {
                    'Content-Type': 'application/ld+json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });

            if (response.status === 200) {
                window.location.href = "/current_book";
            } else {
                console.error('Error saving session:', response.statusText);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };

    return (
        <div className="popup flex-column-space-around-center">
            <h1>Reading session</h1>
            <p>You have already read: <span>{pagesRead}</span> pages</p>
            <div className="session-details flex-column-space-around-center">
                <div className="bookcover flex-row-center-center">
                    <img src={coverUrl} className="cover-img" alt="Book cover" />
                </div>
                <div className="flex-column-space-around-center timer-container">
                    {/*<div id="timer">{updateTimer()}</div>*/}
                    <div id="timer">{time}</div>
                </div>
            </div>
            {/*<button className="popup-light-btn" onClick={handleStart}>Start</button>*/}
            {/*<button className="popup-light-btn" onClick={handlePause}>Pause</button>*/}
            {/*<button className="popup-light-btn" onClick={handleStop}>Stop</button>*/}
            <button className="popup-light-btn" onClick={() => setStart(true)}>Start</button>
            <button className="popup-light-btn" onClick={() => setStart(false)}>Pause</button>
            <button className="popup-light-btn" onClick={stopSession}>Stop</button>
            <a href="/current_book" className="popup-cancel-btn light">Cancel</a>

            {formVisible && (
                <div id="endSessionFormContainer" className="blur">
                    <div className="end-session-popup flex-column-space-around-center">
                        <h2>End session</h2>
                        <form onSubmit={handleSubmit} className="flex-column-space-around-center">
                            <input type="hidden" id="timerValue" name="timerValue" value={time} />
                            <input type="hidden" id="endDate" name="endDate" value={new Date().toISOString()} />
                            <input type="number" id="pageNumber" name="pageNumber" placeholder="Number of page you got to" value={pageNumber} onChange={(e) => setPageNumber(e.target.value)} required />
                            <button type="submit" className="default-btn">Save</button>
                        </form>
                        <button className="popup-cancel-btn" onClick={handleCancel}>Cancel</button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default ReadingSession;
