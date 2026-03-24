<?php
include 'db_connect.php';
$res = oci_parse($conn, "SELECT ORDER_ID, CUSTOMER_ID, TOTAL_AMOUNT, ORDER_DATE FROM SHOP_ORDER WHERE STATUS != 'Finalized' OR STATUS IS NULL ORDER BY ORDER_ID");
oci_execute($res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card { width: 95vw; max-width: 1200px; margin: 24px auto; padding: 42px 36px; background: white; border-radius: 14px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); font-family: "Times New Roman", Times, serif; }
        h2 { text-align: center; font-weight: 700; margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; font-size: 16px; margin-bottom: 24px; }
        th, td { border: 1px solid #ddd; padding: 14px 18px; text-align: left; }
        th { background-color: #007bff; color: white; font-weight: 700; }
        .ui-button-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 24px; }
        .ui-btn { background-color: #007bff; color: white; font-weight: 700; border: none; border-radius: 10px; padding: 1.2em 0; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.07); cursor: pointer; font-size: 1.1rem; display: block; transition: background-color 0.2s ease; }
        .ui-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class="card">
    <h2>Order List</h2>
    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total (₹)</th>
            <th>Date</th>
            <th>Action</th> <!-- New Action column -->
        </tr>
        <?php while($row = oci_fetch_assoc($res)): ?>
        <tr>
            <td><?= htmlspecialchars($row['ORDER_ID']) ?></td>
            <td><?= htmlspecialchars($row['CUSTOMER_ID']) ?></td>
            <td><?= htmlspecialchars($row['TOTAL_AMOUNT']) ?></td>
            <td><?= htmlspecialchars($row['ORDER_DATE']) ?></td>
            <td>
                <form method="post" action="finalize_order.php" style="margin:0;">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['ORDER_ID']) ?>">
                    <button type="submit" class="ui-btn" style="padding:0.4em 0.8em; font-size:0.9rem;">Finalize Order</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
