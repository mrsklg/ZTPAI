async function deleteCurrentUser() {
    const response = await fetch(`http://127.0.0.1:8000/api/delete_user`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
    });
}

document.querySelector('.delete-btn').addEventListener('click', () => {
    deleteCurrentUser();
})