// Existing function to toggle visibility of submenus
function toggleMenu(key) {
    const submenu = document.getElementById(key + '-submenu');
    
    // Toggle visibility (show/hide)
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block'; // Show the submenu
    } else {
        submenu.style.display = 'none'; // Hide the submenu
    }
}

// New function to handle hover effects on submenu items
document.addEventListener('DOMContentLoaded', function () {
    const submenuItems = document.querySelectorAll('.submenu-item');

    submenuItems.forEach(item => {
        const icon = item.querySelector('img.submenu-icon'); // Get the icon inside each submenu item
        const originalIcon = icon.src; // Store the original image source

        // Hover effect: Change the icon when hovering
        item.addEventListener('mouseenter', function () {
            // Update the icon source (this should be the black version of the icon)
            icon.src = icon.src.replace('White', 'Black');
        });

        // Hover out effect: Revert the icon back to the original
        item.addEventListener('mouseleave', function () {
            icon.src = originalIcon; // Revert back to the original icon (white version)
        });
    });
});
