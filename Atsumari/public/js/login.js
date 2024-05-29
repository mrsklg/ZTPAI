document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#login-form');
    const messages = document.querySelector('.messages')

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(form);
        const email = formData.get('email');
        const password = formData.get('password');

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                }),
            });

            const data = await response.json()
            localStorage.setItem('jwt', data.token)

            if (!response.ok) {
                messages.innerHTML = `<p>${data.message}</p>`
                throw new Error('Login failed');
            }

            window.location.href = '/dashboard';

        } catch (error) {
            console.error('Login failed:', error);
        }
    });
});
