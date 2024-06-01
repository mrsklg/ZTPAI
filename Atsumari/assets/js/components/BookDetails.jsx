import React, { useEffect, useState } from 'react';
import axios from 'axios';

const BookDetails = () => {
    const [book, setBook] = useState(null);
    const [isRead, setIsRead] = useState(false);
    const [currentBook, setCurrentBook] = useState(null);
    const [error, setError] = useState('');

    const url = window.location.href;
    const id = url.substring(url.lastIndexOf('/') + 1);

    useEffect(() => {
        const fetchBookDetails = async () => {
            try {
                const bookResponse = await axios.get(`http://127.0.0.1:8000/api/books/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });
                setBook(bookResponse.data);

                const isReadResponse = await axios.get(`http://127.0.0.1:8000/api/books/is-read/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });
                setIsRead(isReadResponse.data.is_read);

                const currentBookResponse = await axios.get(`http://127.0.0.1:8000/api/current_book_data`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });
                setCurrentBook(currentBookResponse.data);

            } catch (error) {
                console.error('Error fetching data:', error.message);
                setError('Error fetching data');
            }
        };

        fetchBookDetails();
    }, [id]);

    const handleDelete = async () => {
        try {
            await axios.delete(`http://127.0.0.1:8000/api/remove_book/${id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            window.location.href = '/books';
        } catch (error) {
            console.error('Failed to delete book:', error.message);
        }
    };

    if (error) {
        return <div>{error}</div>;
    }

    if (!book || currentBook === null) {
        return <div>Loading...</div>;
    }

    const genres = book.genres.map(genre => genre.genre_name).join(', ');
    const authors = book.authors.map(author => `${author.first_name} ${author.last_name}`).join(', ');

    const bookId = book["@id"].slice(11);
    const isCurrBook = book.title === currentBook.title;
    const existCurrBook = currentBook.title !== null;

    const buttons = () => {
        if (isCurrBook) {
            return ( <a
                href={`/reading_session?id=${bookId}`}
                className="popup-light-btn"
                id="continue-reading"
            >
                Continue Reading
            </a>);
        } else if (existCurrBook) {
            return (<a href="/books" className="popup-light-btn light" id="close-popup">Close</a>);
        } else {
            return ( <a
                href={`/reading_session?id=${bookId}`}
                className="popup-light-btn"
                id="continue-reading"
            >
                Start Reading
            </a>);
        }
    }

    return (
        <div className="popup flex-column-space-around-center">
            <h1>Book details</h1>
            <div className="book-details flex-column-space-around-center">
                <div className="bookcover flex-row-center-center">
                    {book.cover_url ? (
                        <img src={book.cover_url} className="cover-img" alt="" />
                    ) : (
                        <div className="bookcover no-cover"><h2>{book.title}</h2></div>
                    )}
                </div>
                <div className="flex-column-space-around-center details-container">
                    <div>
                        <p>Title:</p>
                        <p id="title" className="details-text">{book.title}</p>
                    </div>
                    <div>
                        <p>Author:</p>
                        <p id="author" className="details-text">{authors}</p>
                    </div>
                    <div>
                        <p>Pages:</p>
                        <p id="num-of-pages" className="details-text">{book.num_of_pages}</p>
                    </div>
                    <div>
                        <p>Genre:</p>
                        <p id="genre" className="details-text">{genres}</p>
                    </div>
                </div>
            </div>
            <p id="read-message">{isRead ? "You have already read this book" : ""}</p>
            {!isRead && buttons()}
            {(existCurrBook && isCurrBook || isRead) && <a href="/books" className="popup-cancel-btn light" id="close-popup">Close</a>}
            <button className="popup-cancel-btn light" id="delete-btn" onClick={handleDelete}>Delete Book</button>
        </div>
    );
};

export default BookDetails;