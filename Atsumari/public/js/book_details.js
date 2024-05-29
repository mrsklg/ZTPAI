document.addEventListener('DOMContentLoaded', async () => {
    const url = window.location.href;
    const id = url.substring(url.lastIndexOf('/') + 1);

    try {
        const response = await fetch(`http://127.0.0.1:8000/api/books/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                'Content-Type': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error('Failed to fetch book details');
        }
        const book = await response.json();
        console.log(book)

        const genres = []
        for (const genre of book.genres) {
            genres.push(genre.genre_name)
        }

        const authors = []
        for (const author of book.authors) {
            authors.push(author.first_name + ' ' + author.last_name)
        }

        const isReadRes = await fetch(`http://127.0.0.1:8000/api/books/is-read/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                'Content-Type': 'application/json'
            }
        });

        const isRead = await isReadRes.json();

        const currBookRes = await fetch(`http://127.0.0.1:8000/api/current_book_data`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                'Content-Type': 'application/json'
            }
        });
        const currBook = await currBookRes.json();
        const existCurrBook = (currBook.title !== null)
        const isCurrBook = (book.title === currBook.title)
        console.log(existCurrBook, isCurrBook)
        const bookId = book["@id"].slice(11);
        console.log(bookId)

        document.getElementById('title').innerText = book.title;
        document.getElementById('author').innerText = authors.join(', ');
        document.getElementById('num-of-pages').innerText = book.num_of_pages;

        document.getElementById('genre').innerText = genres.join(', ');
        document.querySelector('.cover-img').src = book.cover_url;

        const continueReadingLink = document.getElementById('continue-reading');
        if (isCurrBook) {
            continueReadingLink.href = `/reading_session?id=${bookId}`;
        } else if (existCurrBook) {
            continueReadingLink.remove();
            document.querySelector('#close-popup').classList.remove("popup-cancel-btn")
            document.querySelector('#close-popup').classList.add("popup-light-btn")
            if (isRead) {
                document.querySelector('#read-message').textContent = "You have already read this book";
            }
        } else{
            continueReadingLink.textContent = "Start reading";
            continueReadingLink.href = `/reading_session?id=${bookId}`;
        }


        const deleteBtn = document.querySelector("#delete-btn")
        deleteBtn.addEventListener('click', async () => {
            const response = await fetch(`http://127.0.0.1:8000/api/remove_book/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            const data = await response.json()
            console.log(data);
            window.location.href = '/books'
            if (!response.ok) {
                throw new Error('Failed to fetch book details');
            }
        })
    } catch (error) {
        console.error(error);
    }
});