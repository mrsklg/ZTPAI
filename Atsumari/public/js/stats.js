async function getGeneralStats() {
    const booksCountGen = document.querySelector('#books-read-gen');
    const readingSpeedGen = document.querySelector('#reading-speed-gen');

    const response = await fetch(`http://127.0.0.1:8000/api/stats/user-reading-stats`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
    });

    const data = await response.json();

    readingSpeedGen.textContent = Number(data['avg_reading_speed']).toFixed(2);
    booksCountGen.textContent = data['total_books'];
}

document.addEventListener('DOMContentLoaded', async function () {
    const allTimeCtx = document.getElementById('allTimeChart').getContext('2d');
    const lastYearCtx = document.getElementById('lastYearChart').getContext('2d');

    const resAllTime = await fetch(`http://127.0.0.1:8000/api/stats/books-read-per-year`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
    });

    const dataAllTime = await resAllTime.json();
    const years = [];
    const booksByYear = [];
    dataAllTime.forEach(res => {
        years.push(res['year']);
        booksByYear.push([res['book_count']]);
    })

    const resLastYear = await fetch(`http://127.0.0.1:8000/api/stats/books-read-last-year`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
        }
    });

    const dataLastYear = await resLastYear.json();
    const booksLastYear = Array(12).fill(0);
    dataLastYear.forEach(result => booksLastYear[result['month'] - 1] = result['book_count']);

    getGeneralStats();

    Chart.defaults.backgroundColor = '#FFF8E1';
    Chart.defaults.borderColor = '#FFF8E1';
    Chart.defaults.color = '#FFF8E1';


    const allTimeData = {
        labels: years,
        datasets: [{
            label: 'Books Read',
            data: booksByYear,
            backgroundColor: '#a23e48',
        }]
    };

    const lastYearData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Books Read',
            data: booksLastYear,
            backgroundColor: '#a23e48',
        }]
    };

    new Chart(allTimeCtx, {
        type: 'bar',
        data: allTimeData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    new Chart(lastYearCtx, {
        type: 'bar',
        data: lastYearData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});