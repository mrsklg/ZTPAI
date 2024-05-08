async function fetchBooksAndUpdateView() {
    try {
        const response = await fetch('http://127.0.0.1:8000/api/books', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch books');
        }

        const data = await response.json();

        let bookListContainer = document.querySelector('.book-list');

        if (!bookListContainer) {
            bookListContainer = document.createElement('div');
            bookListContainer.classList.add('book-list');
            if (data.length > 0) {
                document.querySelector('main').innerHTML = '';
            }
            document.querySelector('main').appendChild(bookListContainer);
        }

        data.forEach(book => {
            const bookTile = document.createElement('div');
            bookTile.classList.add('collection-tile');
            bookTile.id = book.title;

            const bookLink = document.createElement('a');
            const bookId = book.id;
            bookLink.href = `/books/${bookId}`;

            const bookImage = document.createElement('img');
            bookImage.src = book.coverUrl;
            bookImage.alt = 'book cover';

            bookLink.appendChild(bookImage);
            bookTile.appendChild(bookLink);
            bookListContainer.appendChild(bookTile);
        });
    } catch (error) {
        console.error('Error fetching books:', error.message);
    }
}

window.addEventListener('load', fetchBooksAndUpdateView);