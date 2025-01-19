function toggleMenu(key) {
    const submenu = document.getElementById(key + '-submenu');
    
    // Toggle visibility (show/hide)
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block'; // Show the submenu
    } else {
        submenu.style.display = 'none'; // Hide the submenu
    }
}
