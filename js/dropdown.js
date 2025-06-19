// Toggle submenu on click
document.querySelectorAll('.submenu-toggle').forEach(toggle => {
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const parent = this.parentElement;
        const submenu = parent.querySelector('.submenu');
        const isOpen = parent.classList.contains('open');

        // Close all first if needed
        document.querySelectorAll('.has-submenu.open').forEach(p => {
            p.classList.remove('open');
            p.querySelector('.submenu').style.display = 'none';
        });

        // Toggle current
        if (!isOpen) {
            parent.classList.add('open');
            submenu.style.display = 'block';
        }
    });
});

// Prevent submenu click from closing itself
document.querySelectorAll('.submenu').forEach(submenu => {
    submenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

// Optional: Close if click outside
document.addEventListener('click', function(e) {
    document.querySelectorAll('.has-submenu.open').forEach(parent => {
        if (!parent.contains(e.target)) {
            parent.classList.remove('open');
            const submenu = parent.querySelector('.submenu');
            submenu.style.display = 'none';
        }
    });
});
