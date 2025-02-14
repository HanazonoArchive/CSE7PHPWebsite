<?php
session_start();
$items = $_SESSION["quotation_items"] ?? [];


if (empty($items)) {
    echo "<p>No quotation items available.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Print</title>
    <link rel="stylesheet" href="print-quotation.css">
</head>
<body>

<h2>Quotation Items</h2>

<table>
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item["item"]) ?></td>
                <td><?= htmlspecialchars($item["description"]) ?></td>
                <td><?= htmlspecialchars($item["quantity"]) ?></td>
                <td><?= htmlspecialchars($item["price"]) ?></td>
                <td><?= htmlspecialchars($item["total"]) ?></td>
            </tr>
        <?php endforeach; ?>
        <td></td>
        <td></td>
        <td></td>
        <td>Total Amount</td>
        <td>000.00</td>
    </tbody>
</table>

<button class="print-btn" onclick="window.print()">Print</button>

</body>
</html>
