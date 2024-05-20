const getCurrentBook = async () => {
    const res = await fetch(`http://127.0.0.1:8000/api/current_book`, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
            'Content-Type': 'application/json'
        }
    });
    const data = await res.json();
    console.log(data);
}

getCurrentBook()