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
        const usersData = await response.json();
        const users = usersData['hydra:member'];

        const usersContainer = document.querySelector('.users-container');
        usersContainer.innerHTML = '';

        users.forEach(user => {
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
            userNameParagraph.textContent = `${user['id_user_details'].first_name} ${user['id_user_details'].last_name}`;
            const emailParagraph = document.createElement('p');
            emailParagraph.classList.add('dark', 'email');
            emailParagraph.textContent = user.email;
            userDetailsDiv.appendChild(userNameParagraph);
            userDetailsDiv.appendChild(emailParagraph);

            const deleteUserButton = document.createElement('button');
            deleteUserButton.classList.add('small-btn');
            deleteUserButton.textContent = 'Delete User';
            deleteUserButton.dataset.email = user.email; // Ustawiamy atrybut data-email na adres e-mail użytkownika

            userDiv.appendChild(userImgDiv);
            userDiv.appendChild(userDetailsDiv);
            userDiv.appendChild(deleteUserButton);

            usersContainer.appendChild(userDiv);
        });
    } catch (error) {
        console.error(error);
    }
});
