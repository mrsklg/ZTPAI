import React, { useEffect, useState } from 'react';
import axios from 'axios';

const Collection = () => {
    const [books, setBooks] = useState([]);
    const [error, setError] = useState('');

    useEffect(() => {
        const fetchBooks = async () => {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/books', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.data.length === 0) {
                    setError('You don\'t have any books in your collection.');
                } else {
                    setBooks(response.data);
                }
            } catch (error) {
                console.error('Error fetching books:', error.message);
                setError('Error fetching books');
            }
        };

        fetchBooks();
    }, []);

    return (
        <main className="flex-column-space-around-center collection-main">
            {error ? (
                <h1>{error}</h1>
            ) : (
                <div className="book-list flex-row-center-center">
                    {books.map(book => (
                        <div key={book.id} className={`collection-tile ${!book.coverUrl ? 'no-cover flex-row-center-center' : ''}`}>
                            <a href={`/books/${book.id}`}>
                                {book.coverUrl ? (
                                    <img src={book.coverUrl} alt="book cover" />
                                ) : (
                                    <h2>{book.title}</h2>
                                )}
                            </a>
                        </div>
                    ))}
                </div>
            )}
        </main>
    );
};

export default Collection;
