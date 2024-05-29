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