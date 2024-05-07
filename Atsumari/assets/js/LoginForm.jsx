import React, { useState } from 'react';
import axios from 'axios';

const LoginForm = () => {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState(null);

    const handleEmailChange = (e) => {
        setEmail(e.target.value);
    };

    const handlePasswordChange = (e) => {
        setPassword(e.target.value);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await axios.post('127.0.0.1:8000/login', {
                email,
                password,
            });
            // Handle successful login
            console.log('Login successful:', response.data);
        } catch (error) {
            // Handle login error
            setError(error.response.data.message); // Assuming API returns error message
        }
    };

    return (
        <main className="flex-column-space-around-center">
            <img src="/images/Atsumari.png" alt="Atsumari Logo" />
            <div className="form-container">
                <div className="messages">
                    {error ? (
                        <p>{error}</p>
                    ) : (
                        <h2>Welcome back!</h2>
                    )}
                </div>
                <form className="flex-column-space-around-center" onSubmit={handleSubmit}>
                    <input type="text" name="email" placeholder="Email" value={email} onChange={handleEmailChange} required />
                    <input type="password" name="password" placeholder="Password" value={password} onChange={handlePasswordChange} required />
                    <button type="submit" className="default-btn">Login</button>
                    <div className="check-container">
                        <label>
                            <input type="checkbox" name="_remember_me" className="check-form" />Remember me
                        </label>
                    </div>
                </form>
                <p className="comment">Create an account. <a href="/signup" className="popup-cancel-btn">Sign Up</a></p>
            </div>
        </main>
    );
};

export default LoginForm;
