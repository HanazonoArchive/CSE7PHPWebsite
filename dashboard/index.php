<!-- index.php -->

<?php include('includes/header.php'); ?>
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<div class="container">
    <?php include('includes/sidebar.php'); ?>
    <div class="main-content">
        <h2>Welcome to the Dashboard</h2>
        <p>This is the main dashboard area where you can put charts, tables, or other data.</p>

        <!-- Example of dynamic content -->
        <h3>Latest Activity</h3>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Activity</th>
                <th>Date</th>
            </tr>
            <tr>
                <td>1</td>
                <td>User logged in</td>
                <td>2025-01-16</td>
            </tr>
            <tr>
                <td>2</td>
                <td>System update completed</td>
                <td>2025-01-15</td>
            </tr>
        </table>
    </div>
</div>
<?php include('includes/footer.php'); ?>
