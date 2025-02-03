<?php
// utility/HelperFunctions.php

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function formatDate($date) {
    return date("F j, Y", strtotime($date));
}
?>
