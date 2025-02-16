<?php
session_start(); // Ensure the session is started

echo "<h2>Session Debugging:</h2>";
echo "<pre>";

// Check and print each session variable separately
if (!empty($_SESSION)) {
    echo "<h3>dHeader:</h3>";
    print_r($_SESSION['dHeader'] ?? "No data found");

    echo "<h3>dBody:</h3>";
    print_r($_SESSION['dBody'] ?? "No data found");

    echo "<h3>dFooter:</h3>";
    print_r($_SESSION['dFooter'] ?? "No data found");

    echo "<h3>dTechnicianInfo:</h3>";
    print_r($_SESSION['dTechnicianInfo'] ?? "No data found");

    echo "<h3>Items:</h3>";
    print_r($_SESSION['items'] ?? "No data found");
} else {
    echo "No session data found.";
}

echo "</pre>";

echo "<h2>Session Debugging:</h2>";

// Start table
echo "<h3>Items Table:</h3>";
if (!empty($_SESSION['items']) && is_array($_SESSION['items'])) {
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr>
            <th>Item</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
          </tr>";

    foreach ($_SESSION['items'] as $item) {
        echo "<tr>
                <td>{$item['item']}</td>
                <td>{$item['description']}</td>
                <td>{$item['quantity']}</td>
                <td>{$item['price']}</td>
                <td>{$item['total']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No items found in session.";
}
?>