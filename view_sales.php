<?php
include 'db_connect.php';

$query = "SELECT s.sales_id, o.order_id, o.total_amount, s.sale_date
          FROM sales s
          JOIN shop_Order o ON s.order_id = o.order_id
          ORDER BY s.sale_date DESC";

$stmt = oci_parse($conn, $query);
oci_execute($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Records</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card { width: 95vw; max-width: 1200px; margin: 24px auto; padding: 44px 36px; background: white; border-radius: 14px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); font-family: "Times New Roman", Times, serif; }
        h2 { text-align: center; font-weight: 700; font-size: 2rem; margin-bottom: 22px; }
        table { border-collapse: collapse; width: 100%; font-size: 16px; }
        th, td { border: 1px solid #ddd; padding: 14px 20px; text-align: left; }
        th { background-color: #007bff; color: white; font-weight: 700; }
        .ui-button-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .ui-btn { background-color: #007bff; color: white; font-family: "Times New Roman", Times, serif; font-weight: 700; padding: 1.2em 0; text-align: center; border: none; border-radius: 10px; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.07); cursor: pointer; font-size: 1.1rem; display: block; transition: background-color 0.3s ease; }
        .ui-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <h2>Sales Records</h2>
    <table>
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = oci_fetch_assoc($stmt)): ?>
            <tr>
                <td><?= htmlspecialchars($row['SALES_ID']) ?></td>
                <td><?= htmlspecialchars($row['ORDER_ID']) ?></td>
                <td>₹<?= number_format($row['TOTAL_AMOUNT'], 2) ?></td>
                <td><?= htmlspecialchars($row['SALE_DATE']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
