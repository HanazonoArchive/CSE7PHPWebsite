$(document).on('click', '.sidebar-link', function (e) {
    e.preventDefault();
    const page = $(this).data('page');
    $('#main-content').load(page);
});
