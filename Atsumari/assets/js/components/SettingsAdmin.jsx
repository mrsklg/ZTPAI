import React, { useEffect, useState } from 'react';
import axios from 'axios';

const SettingsAdmin = () => {
    const [users, setUsers] = useState([]);
    const [currentUserId, setCurrentUserId] = useState(null);

    useEffect(() => {
        const fetchUsers = async () => {
            try {
                const response = await axios.get('http://127.0.0.1:8000/api/users', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                        'Content-Type': 'application/json'
                    }
                });
                setUsers(response.data.usersData);
                setCurrentUserId(response.data.currentUserId);
            } catch (error) {
                console.error('Failed to fetch users data', error);
            }
        };

        fetchUsers();
    }, []);

    const handleDeleteUser = async (id) => {
        try {
            await axios.delete(`http://127.0.0.1:8000/api/users/${id}`, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            setUsers(users.filter(user => user.id !== id));
        } catch (error) {
            console.error('Error deleting user:', error);
        }
    };

    const handleDeleteCurrentUser = async () => {
        await handleDeleteUser(currentUserId);
        window.location.href = '/logout';
    };

    const handleLogout = () => {
        window.location.href = '/logout';
    };

    return (
        <main className="flex-column-space-around-center settings-main">
            <div className="basic-settings flex-column-space-around-center">
                <div className="flex-column-space-around-center images">
                    <img className="logo" src="/images/Atsumari.png" alt="Atsumari logo" />
                    <img src="/images/avatar-img.png" className="avatar-settings" alt="avatar image" />
                </div>
                <div className="form-container">
                    <h2 className="manage-text">Manage Your Account</h2>
                    <button className="default-btn del-btn" onClick={handleDeleteCurrentUser}>Delete account</button>
                    <button className="default-btn" onClick={handleLogout}>Logout</button>
                </div>
            </div>
            <div className="history flex-column-space-around-center">
                <h4>Manage Users</h4>
                <div className="users-container">
                    {users.map(user => (
                        <div key={user.id} className="session-info flex-row-center-center user-info">
                            <div className="flex-column-space-around-center session-details user-img">
                                <img className="avatar-img-admin" src="/images/avatar-img.png" alt="avatar image" />
                            </div>
                            <div className="flex-column-space-around-center session-details">
                                <p className="dark">{user.first_name} {user.last_name}</p>
                                <p className="dark email">{user.email}</p>
                            </div>
                            <button className="small-btn del-btn" onClick={() => handleDeleteUser(user.id)}>Delete User</button>
                        </div>
                    ))}
                </div>
            </div>
        </main>
    );
};

export default SettingsAdmin;
