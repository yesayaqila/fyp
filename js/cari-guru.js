function filterTeachers() {
    const input = document.getElementById('searchInput');
    const filter = input.value.trim();
    const dropdown = document.getElementById('searchDropdown');
    const cards = document.getElementsByClassName('teacher-card');
    let matchFound = false;

    // Filter visible cards
    for (let i = 0; i < cards.length; i++) {
        const nameElement = cards[i].getElementsByClassName('teacher-name')[0];
        const nameText = nameElement.textContent || nameElement.innerText;

        if (nameText.toUpperCase().includes(filter.toUpperCase())) {
            cards[i].style.display = "";
            matchFound = true;
        } else {
            cards[i].style.display = "none";
        }
    }

    // Fetch from server if no match
    if (!matchFound && filter.length > 0) {
        fetch(`search-teachers.php?query=${encodeURIComponent(filter)}`)
            .then(response => response.json())
            .then(data => {
                dropdown.innerHTML = '';
                dropdown.style.display = 'block';

                if (data.length > 0) {
                    data.forEach(name => {
                        const item = document.createElement('div');
                        item.textContent = name;
                        item.onclick = () => {
                            dropdown.style.display = 'none';
                            confirmAddToPanitia(name);
                        };
                        dropdown.appendChild(item);
                    });
                } else {
                    const item = document.createElement('div');
                    item.textContent = "Tiada padanan dijumpai";
                    dropdown.appendChild(item);
                }
            });
    } else {
        dropdown.innerHTML = '';
        dropdown.style.display = 'none';
    }
}

function confirmAddToPanitia(name) {
    const confirmAdd = confirm(`Tambah ${name} dalam panitia?`);
    if (confirmAdd) {
        alert(`${name} telah ditambah ke dalam panitia.`);
        // TODO: Do something, like redirect or call an AJAX insert function
    }
}
