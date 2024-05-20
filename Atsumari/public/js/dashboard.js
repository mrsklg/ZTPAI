document.addEventListener('DOMContentLoaded', async () => {
    const currentBookDiv = document.querySelector('.current-book > div')

    const getCurrentBook = async () => {
        const res = await fetch(`http://127.0.0.1:8000/api/current_book`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                'Content-Type': 'application/json'
            }
        });
        const data = await res.json();
        return data;
    }

    const book = await getCurrentBook();
    console.log(book)
    if (book.title) {
        const image = currentBookDiv.querySelector('img')
        const heading = currentBookDiv.querySelector('h2')
        image.src = book.cover_url;
        heading.textContent = `You are reading ${book.title}`
    }
})