$(document).ready(function () {
    // When a sidebar link is clicked
    $('.sidebar-link').on('click', function (e) {
        e.preventDefault();  // Prevent default anchor behavior

        // Get the page to load from the data-page attribute
        var page = $(this).data('page');

        // Load the page into the main-content div
        $('#main-content').load(page);
    });

    // Load the default page (dashboard.php) on page load
    $('#main-content').load('includes/pages/dashboard.php');
});
