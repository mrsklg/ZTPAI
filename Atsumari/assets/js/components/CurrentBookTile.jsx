import React, { useState, useEffect } from 'react';
import axios from 'axios';

const CurrentBookTile = () => {
    const [bookData, setBookData] = useState({ title: '', cover_url: '' });

    useEffect(() => {
        const fetchData = async () => {
            try {
                const res = await axios.get('http://127.0.0.1:8000/api/current_book_data', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });
                setBookData(res.data);
            } catch (error) {
                console.error("Error fetching the current book data", error);
            }
        };

        fetchData();
    }, []);

    return (
        <a href="/current_book" className="current-book">
            <div className="dashboard-tile">
                <img src={bookData.cover_url || 'https://images.pexels.com/photos/6373289/pexels-photo-6373289.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1'} alt="Current Book Cover" />
                <h2>
                    {bookData.title ? `You are reading ${bookData.title}` : 'Currently, you are not reading any book'}
                </h2>
            </div>
        </a>
    );
}

export default CurrentBookTile;
