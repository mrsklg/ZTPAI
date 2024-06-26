import React from 'react';
import DashboardTile from './DashboardTile';
import CurrentBookTile from './CurrentBookTile';


const Dashboard = () => {
    return (
        <main className="flex-column-space-around-center dashboard-main">
            <div className="tile-container flex-column-space-around-center">
                <h1>Are you ready for a new adventure?</h1>
                <CurrentBookTile />
                <DashboardTile
                    link="/add_book"
                    imgSrc="https://images.pexels.com/photos/2203051/pexels-photo-2203051.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    title="Add book"
                />
                <DashboardTile
                    link="/books"
                    imgSrc="https://images.pexels.com/photos/2128249/pexels-photo-2128249.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    title="See all your books"
                />
            </div>
        </main>
    );
}

export default Dashboard;
