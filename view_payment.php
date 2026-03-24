<?php
include 'db_connect.php';

// Select payment records with related customer names for display
$stmt = oci_parse($conn, "
    SELECT p.payment_id, p.order_id, p.amount, p.pay_date, p.pay_mode, c.cname
    FROM payment p
    JOIN shop_order o ON p.order_id = o.order_id
    JOIN customer c ON o.customer_id = c.customer_id
    ORDER BY p.pay_date DESC
");
oci_execute($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments List</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        table {
            width: 100%; border-collapse: collapse; font-size: 16px;
        }
        th, td {
            border: 1px solid #ddd; padding: 12px 15px; text-align: left;
        }
        th {
            background-color: #007bff; color: white; font-weight: 700;
        }
        .container {
            max-width: 1200px; margin: 30px auto; padding: 20px;
            font-family: "Times New Roman", Times, serif;
            background: #fff; border-radius: 8px; box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center; margin-bottom: 20px; font-weight: bold;
        }
        a.button {
            display: inline-block; margin-bottom: 20px;
            background-color: #007bff; color: white;
            padding: 10px 18px; text-decoration: none; border-radius: 5px;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Payments List</h2>
    <a href="index.php" class="button">Back to Dashboard</a>
    <a href="add_payment.php" class="button">Add Payment</a>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Amount (₹)</th>
                <th>Payment Date</th>
                <th>Payment Mode</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = oci_fetch_assoc($stmt)): ?>
            <tr>
                <td><?= htmlspecialchars($row['PAYMENT_ID']) ?></td>
                <td><?= htmlspecialchars($row['ORDER_ID']) ?></td>
                <td><?= htmlspecialchars($row['CNAME']) ?></td>
                <td><?= number_format($row['AMOUNT'], 2) ?></td>
                <td><?= htmlspecialchars(date('d-M-Y', strtotime($row['PAY_DATE']))) ?></td>
                <td><?= htmlspecialchars($row['PAY_MODE']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
