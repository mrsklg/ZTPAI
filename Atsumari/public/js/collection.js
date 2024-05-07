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
        console.log(data)
        const books = data['hydra:member'];

        const bookListContainer = document.querySelector('.book-list');

        books.forEach(book => {
            console.log(book)
            const bookTile = document.createElement('div');
            bookTile.classList.add('collection-tile');
            bookTile.id = book.title;

            const bookLink = document.createElement('a');
            const bookId = book['@id'].split('/').pop();
            bookLink.href = `/books/${bookId}`;

            const bookImage = document.createElement('img');
            bookImage.src = book.cover_url;
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
