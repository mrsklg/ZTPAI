// const addBookForm = document.querySelector('#bookForm')
//
// addBookForm.addEventListener('submit', async e => {
//     e.preventDefault();
//
//     // const formData = new FormData(addBookForm);
//     //
//     // const value = Object.fromEntries(formData.entries());
//     // console.log(value)
//     //
//     // const response = await fetch("/api/add_book_to_db", {
//     //     method: "POST",
//     //     headers: {
//     //         'Authorization': `Bearer ${localStorage.getItem('jwt')}`
//     //     },
//     //     body: formData
//     // });
//     // const data = await response.json();
//     // console.log(data);
//
//     const formData = new FormData();
//     const title = document.getElementById("title").value.trim();
//     const pages = parseInt(document.getElementById("pages").value.trim()) || 0;
//     const genre = document.getElementById("genre").value.trim();
//     const author = document.getElementById("author").value.trim();
//     const coverFile = document.getElementById("file").files[0];
//
//     // Convert genre and author to arrays of objects
//     const genres = [{ genre_name: genre }];
//     const [firstName, lastName] = author.split(' ');
//     const authors = [{ first_name: firstName, last_name: lastName }];
//
//     formData.append('title', title)
//     formData.append('num_of_pages', pages)
//     formData.append('genres', genres)
//     formData.append('authors', authors)
//     formData.append('cover_url', coverFile ? URL.createObjectURL(coverFile) : "")
//
//     console.log(Object.fromEntries(formData.entries()))
//
//     const response = await fetch("/api/add_book_to_db", {
//         method: "POST",
//         headers: {
//             'Authorization': `Bearer ${localStorage.getItem('jwt')}`
//         },
//         body: formData
//     });
//     const data = await response.json();
//     console.log(data);
// })

// document.addEventListener('DOMContentLoaded', function() {
//     let collectionHolders = document.querySelectorAll('div[data-prototype]');
//
//     collectionHolders.forEach(function(holder) {
//         holder.dataset.index = holder.querySelectorAll('input').length;
//
//         let addButton = document.createElement('button');
//         addButton.textContent = 'Add';
//         holder.appendChild(addButton);
//
//         addButton.addEventListener('click', function() {
//             let prototype = holder.dataset.prototype;
//             let index = holder.dataset.index;
//             let newForm = prototype.replace(/__name__/g, index);
//             holder.dataset.index++;
//             let newFormElement = document.createElement('div');
//             newFormElement.innerHTML = newForm;
//             holder.insertBefore(newFormElement, addButton);
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function() {
    function addFormToCollection(collectionHolder) {
        var prototype = collectionHolder.getAttribute('data-prototype');
        var index = collectionHolder.dataset.index;
        var newForm = prototype.replace(/__name__/g, index);
        collectionHolder.dataset.index = parseInt(index) + 1;
        var newFormLi = document.createElement('li');
        newFormLi.innerHTML = newForm;
        collectionHolder.appendChild(newFormLi);
    }

    document.querySelector('.add-author-button').addEventListener('click', function() {
        var collectionHolder = document.querySelector('.authors-collection');
        addFormToCollection(collectionHolder);
    });

    document.querySelector('.add-genre-button').addEventListener('click', function() {
        var collectionHolder = document.querySelector('.genres-collection');
        addFormToCollection(collectionHolder);
    });
});