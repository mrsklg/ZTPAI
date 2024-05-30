import React from 'react';

const Index = () => {
    return (
        <main className="flex-column-space-around-center">
            <img src="/images/Atsumari.png" alt="Atsumari" />
            <p className="description">
                Have you lost track of your reading record? Do you want to know your reading speed? Do you want to manage your bookshelf?
                <br /><br />
                With Atsumari it is easy. Create your account and start managing your books today!
            </p>
            <a className="default-btn" href="/signup">Create account</a>
            <p className="comment">...or <a href="/login" className="popup-cancel-btn">Log In</a></p>
        </main>
    );
}

export default Index;
