document.addEventListener("DOMContentLoaded", function () {
    // Get the current path of the page and normalize it
    const currentPath = window.location.pathname.replace(/\/$/, ""); // Remove trailing slash

    // Get all navbar links
    const navLinks = document.querySelectorAll('.navbar a');

    // Loop through the links to check if their href matches the current path
    navLinks.forEach(link => {
        // Get the href of the link and normalize it
        const linkPath = new URL(link.href, window.location.origin).pathname.replace(/\/$/, "");

        // Compare the current path with the link path
        if (linkPath === currentPath) {
            // Add 'active' class to the current link
            link.classList.add('active');
        } else {
            // Remove 'active' class if not the current page
            link.classList.remove('active');
        }
    });
});