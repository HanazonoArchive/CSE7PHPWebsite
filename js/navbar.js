document.querySelectorAll('.dropdown').forEach(button => {
    button.addEventListener('click', function (e) {
        // Prevent the click event from propagating to the document
        e.stopPropagation();
        
        // Close other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== this.querySelector('.dropdown-menu')) {
                menu.style.display = 'none';
                menu.style.opacity = 0;
                menu.style.visibility = 'hidden';
            }
        });
        
        // Toggle the current dropdown
        const menu = this.querySelector('.dropdown-menu');
        if (menu.style.display === 'block') {
            menu.style.display = 'none';
            menu.style.opacity = 0;
            menu.style.visibility = 'hidden';
        } else {
            menu.style.display = 'block';
            menu.style.opacity = 1;
            menu.style.visibility = 'visible';
        }
    });
});

// Close dropdown when clicking anywhere else
document.addEventListener('click', function (e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
            menu.style.opacity = 0;
            menu.style.visibility = 'hidden';
        });
    }
});
