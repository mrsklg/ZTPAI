async function deleteUser(id) {
    const response = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
        method: 'DELETE',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('http://127.0.0.1:8000/api/users', {
                method: 'GET',
                headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                    'Content-Type': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error('Failed to fetch users data');
        }
        const data = await response.json();
        const usersData = data['usersData'];

        const deleteLoggedUsrBtn = document.querySelector('#delete-logged-usr-btn');
        deleteLoggedUsrBtn.dataset.userId = data['currentUserId']

        const usersContainer = document.querySelector('.users-container');
        usersContainer.innerHTML = '';

        usersData.forEach(user => {
            const userDiv = document.createElement('div');
            userDiv.classList.add('session-info', 'flex-row-center-center', 'user-info');

            const userImgDiv = document.createElement('div');
            userImgDiv.classList.add('flex-column-space-around-center', 'session-details', 'user-img');
            const avatarImg = document.createElement('img');
            avatarImg.classList.add('avatar-img-admin');
            avatarImg.src = 'images/avatar-img.png';
            avatarImg.alt = 'avatar image';
            userImgDiv.appendChild(avatarImg);

            const userDetailsDiv = document.createElement('div');
            userDetailsDiv.classList.add('flex-column-space-around-center', 'session-details');
            const userNameParagraph = document.createElement('p');
            userNameParagraph.classList.add('dark');
            userNameParagraph.textContent = `${user.first_name} ${user.last_name}`;
            const emailParagraph = document.createElement('p');
            emailParagraph.classList.add('dark', 'email');
            emailParagraph.textContent = user.email;
            userDetailsDiv.appendChild(userNameParagraph);
            userDetailsDiv.appendChild(emailParagraph);

            const deleteUserButton = document.createElement('button');
            deleteUserButton.classList.add('small-btn');
            deleteUserButton.classList.add('del-btn');
            deleteUserButton.textContent = 'Delete User';
            deleteUserButton.dataset.userId = user.id;

            userDiv.appendChild(userImgDiv);
            userDiv.appendChild(userDetailsDiv);
            userDiv.appendChild(deleteUserButton);

            usersContainer.appendChild(userDiv);
        });

        const delBtns = document.querySelectorAll('.del-btn');
        const usersArray = document.querySelectorAll(".user-info");
        delBtns.forEach(btn => btn.addEventListener('click', () => deleteUser(btn.dataset.userId)))
        for (let user of usersArray) {
            user.addEventListener("click", () => {
                user.remove();
            });
        }
    } catch (error) {
        console.error(error);
    }
});
