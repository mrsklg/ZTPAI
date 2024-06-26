import React from 'react';

const Nav = (role) => {
    console.log(role.role)
    return (
        <nav className="flex-row-center-center navbar">
            <ul className="flex-row-center-center nav-list">
                <li>
                    <a href="/dashboard">
                        <i className="fa-solid fa-house fa-2x"></i>
                    </a>
                </li>
                <li>
                    <a href="/current_book">
                        <i className="fa-solid fa-book fa-2x"></i>
                    </a>
                </li>
                <li>
                    <a href="/books">
                        <i className="fa-solid fa-box fa-2x"></i>
                    </a>
                </li>
                <li>
                    <a href="/stats">
                        <i className="fa-solid fa-chart-column fa-2x"></i>
                    </a>
                </li>
                <li>
                    <a href={role.role === 'ROLE_ADMIN' ? "/settings_admin" : "/settings"} className="nav-avatar">
                        <img className="avatar-img-nav" src="/images/avatar-img.png" alt="Avatar"/>
                    </a>
                </li>
            </ul>
        </nav>
    );
};

export default Nav;