// document.addEventListener('DOMContentLoaded', async () => {
//     const currentBookDiv = document.querySelector('.current-book > div')
//
//     const res = await fetch(`http://127.0.0.1:8000/api/current_book_data`, {
//         method: 'GET',
//         headers: {
//             'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
//             'Content-Type': 'application/json'
//         }
//     });
//     const data = await res.json();
//
//     if (data.title) {
//         const image = currentBookDiv.querySelector('img')
//         const heading = currentBookDiv.querySelector('h2')
//         image.src = data.cover_url;
//         heading.textContent = `You are reading ${data.title}`
//     }
// })