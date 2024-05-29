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
            document.querySelector('main').innerHTML = '<h1>You don\'t have any books in your collection.</h1>';
        }

        const data = await response.json();

        let bookListContainer = document.querySelector('.book-list');

        if (!bookListContainer) {
            bookListContainer = document.createElement('div');
            bookListContainer.classList.add('book-list');
            bookListContainer.classList.add('flex-row-center-center');
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

            if (book.coverUrl) {
                const bookImage = document.createElement('img');
                bookImage.src = book.coverUrl;
                bookImage.alt = 'book cover';
                bookLink.appendChild(bookImage);
            } else {
                const par = document.createElement('h2');
                par.textContent = book.title;
                bookLink.appendChild(par);
                bookTile.classList.toggle('no-cover');
                bookTile.classList.toggle('flex-row-center-center')
            }

            bookTile.appendChild(bookLink);
            bookListContainer.appendChild(bookTile);
        });
    } catch (error) {
        console.error('Error fetching books:', error.message);
    }
}

window.addEventListener('DOMContentLoaded', fetchBooksAndUpdateView);