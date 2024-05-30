import React from 'react';
import DashboardTile from './DashboardTile';
import CurrentBook from './CurrentBook';


const Dashboard = () => {
    return (
        <main className="flex-column-space-around-center dashboard-main">
            <div className="tile-container flex-column-space-around-center">
                <h1>Are you ready for a new adventure?</h1>
                <CurrentBook />
                {/*<DashboardTile*/}
                {/*    link="/current_book"*/}
                {/*    imgSrc="https://images.pexels.com/photos/6373289/pexels-photo-6373289.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"*/}
                {/*    title="Currently, you are not reading any book"*/}
                {/*/>*/}
                <DashboardTile
                    link="/add_book"
                    imgSrc="https://images.pexels.com/photos/2203051/pexels-photo-2203051.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    title="Add book"
                />
                <DashboardTile
                    link="/collection"
                    imgSrc="https://images.pexels.com/photos/2128249/pexels-photo-2128249.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                    title="See all your books"
                />
            </div>
        </main>
    );
}

export default Dashboard;
