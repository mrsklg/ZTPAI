import React from 'react';
import axios from 'axios';

const Settings = () => {
    const handleDeleteAccount = async () => {
        try {
            await axios.delete('http://127.0.0.1:8000/api/delete_user', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            window.location.href = '/logout';
        } catch (error) {
            console.error('Error deleting account:', error);
        }
    };

    const logout = () => {
        window.location.href = '/logout';
    }

    return (
        <main className="flex-column-space-around-center settings-main">
            <div className="flex-column-space-around-center images">
                <img src="/images/Atsumari.png" alt="Atsumari logo" />
                <img src="/images/avatar-img.png" className="avatar-settings" alt="avatar image" />
            </div>
            <div className="form-container">
                <h2 className="manage-text">Manage Your Account</h2>
                <button className="default-btn delete-btn" onClick={handleDeleteAccount}>Delete account</button>
                <button className="default-btn" type="submit" name="logout" onClick={logout}>Logout</button>
            </div>
        </main>
    );
};

export default Settings;
