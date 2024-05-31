import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Bar } from 'react-chartjs-2';
import 'chart.js/auto';

const Stats = () => {
    const [generalStats, setGeneralStats] = useState({ readingSpeed: 0, booksRead: 0 });
    const [allTimeData, setAllTimeData] = useState({ labels: [], datasets: [] });
    const [lastYearData, setLastYearData] = useState({ labels: [], datasets: [] });

    useEffect(() => {
        const fetchGeneralStats = async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/stats/user-reading-stats', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            const data = response.data;
            setGeneralStats({
                readingSpeed: Number(data.avg_reading_speed).toFixed(2),
                booksRead: data.total_books
            });
        };

        const fetchAllTimeStats = async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/stats/books-read-per-year', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            const data = response.data;
            const labels = data.map(item => item.year);
            const booksByYear = data.map(item => item.book_count);

            setAllTimeData({
                labels,
                datasets: [{
                    label: 'Books Read',
                    data: booksByYear,
                    backgroundColor: '#a23e48'
                }]
            });
        };

        const fetchLastYearStats = async () => {
            const response = await axios.get('http://127.0.0.1:8000/api/stats/books-read-last-year', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('jwt')}`
                }
            });
            const data = response.data;
            const booksLastYear = Array(12).fill(0);
            data.forEach(result => booksLastYear[result.month - 1] = result.book_count);

            setLastYearData({
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Books Read',
                    data: booksLastYear,
                    backgroundColor: '#a23e48'
                }]
            });
        };

        fetchGeneralStats();
        fetchAllTimeStats();
        fetchLastYearStats();
    }, []);

    return (
        <main className="flex-column-space-around-center stats-main">
            <h1>Your reading stats</h1>
            <div className="stats-container flex-column-space-around-center">
                <div className="chart-container all-time flex-column-space-around-center">
                    <h3>All time</h3>
                    <div className="chart">
                        <Bar data={allTimeData} options={{
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }} />
                    </div>
                </div>
                <div className="chart-container last-year flex-column-space-around-center">
                    <h3>Last year</h3>
                    <div className="chart">
                        <Bar data={lastYearData} options={{
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }} />
                    </div>
                </div>
                <div className="stats-general flex-row-center-center">
                    <div className="flex-column-space-around-center">
                        <p>Your reading speed <span>{generalStats.readingSpeed}</span> page/min</p>
                    </div>
                    <div className="flex-column-space-around-center">
                        <p>You've read <span>{generalStats.booksRead}</span> books so far</p>
                    </div>
                </div>
            </div>
        </main>
    );
};

export default Stats;
