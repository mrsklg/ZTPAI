import React from 'react';

const DashboardTile = ({ link, imgSrc, title }) => {
    return (
        <a href={link}>
            <div className="dashboard-tile">
                <img src={imgSrc} alt={title} />
                <h2>{title}</h2>
            </div>
        </a>
    );
}

export default DashboardTile;