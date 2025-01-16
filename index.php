<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<head>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<div class="container">
    <?php include('includes/sidebar.php'); ?>
    <div class="main-content" id="main-content">
        <?php
        // Default page
        $page = isset($_GET['page']) ? $_GET['page'] : 'includes/pages/dashboard.php';
        
        if (file_exists($page)) {
            include($page);
        } else {
            echo "Page not found.";
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- External script -->
<script src="website-js/index-script.js"></script>
