import React, { useState } from 'react';
import axios from 'axios';

const Login = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [message, setMessage] = useState('');

    const handleLogin = async (event) => {
        event.preventDefault();

        try {
            const response = await axios.post('/login', {
                email,
                password
            }, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            localStorage.setItem('jwt', response.data.token);

            if (response.status !== 200) {
                setMessage(response.data.message || 'Login failed');
                return;
            }

            window.location.href = '/dashboard';
        } catch (error) {
            console.error('Login failed:', error);
            setMessage(error.response.data.message);
        }
    };

    return (
        <main className="flex-column-space-around-center login-main">
            <img src="/images/Atsumari.png" alt="Atsumari Logo" />
            <div className="form-container">
                <div className="messages">
                    {message ? <p>{message}</p> : <h2>Welcome back!</h2>}
                </div>
                <form className="flex-column-space-around-center" onSubmit={handleLogin}>
                    <input
                        type="text"
                        name="email"
                        placeholder="Email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        required
                    />
                    <button type="submit" className="default-btn">Login</button>
                </form>
                <p className="comment">Create an account. <a href="/signup" className="popup-cancel-btn">Sign Up</a></p>
            </div>
        </main>
    );
};

export default Login;
