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

        document.getElementById('title').innerText = book.title;
        document.getElementById('author').innerText = book.authors[0].first_name + ' ' + book.authors[0].last_name;
        document.getElementById('num-of-pages').innerText = book.num_of_pages;
        document.getElementById('genre').innerText = book.genres[0].genre_name;
        document.querySelector('.cover-img').src = book.cover_url;

        const continueReadingLink = document.getElementById('continue-reading');
        if (book.users.length > 0) {
            continueReadingLink.href = `/reading_session/${id}`;
        } else {
            continueReadingLink.remove();
        }

        const closePopupLink = document.getElementById('close-popup');
        if (book.users.length === 0) {
            closePopupLink.href = '/books';
        }
    } catch (error) {
        console.error(error);
    }
});
