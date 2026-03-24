<?php
include 'db_connect.php';
$res = oci_parse(
    $conn,
    "SELECT od.order_detail_id, od.order_id, e.equipment_name, od.quantity, od.price
     FROM Order_Details od
     JOIN Equipment e ON od.equipment_id = e.equipment_id
     ORDER BY od.order_detail_id DESC"
);
oci_execute($res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details List</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        .card { width: 95vw; max-width: 1200px; margin: 22px auto; background: #fff; border-radius: 14px; padding: 38px 32px; box-shadow: 0 4px 25px rgba(0,0,0,0.08); font-family: "Times New Roman", Times, serif; }
        h2 { text-align: center; margin-bottom: 25px; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; font-size: 17px; margin-bottom: 24px; }
        th, td { border: 1px solid #ddd; padding: 13px 18px; text-align: left; }
        th { background-color: #007bff; color: white; font-weight: 700; }
        .actions a { background-color: #007bff; color: white; padding: 8px 12px; border-radius: 8px; text-decoration: none; margin-right: 8px; font-weight: 600; font-family: "Times New Roman", Times, serif; transition: background-color 0.3s ease; }
        .actions a.delete { background-color: #d9534f; }
        .actions a:hover { opacity: 0.85; }
        .actions a.delete:hover { opacity: 0.8; }
        .ui-button-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .ui-btn { background-color: #007bff; color: white; font-weight: 700; border: none; border-radius: 10px; padding: 1.15em 0; text-align: center; text-decoration: none; box-shadow: 0 2px 8px rgba(0,0,0,0.07); cursor: pointer; font-size: 1.1rem; display: block; transition: background-color 0.2s; }
        .ui-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class="card">
    <h2>Order Details List</h2>
    <div class="ui-button-grid">
        <a href="add_order_detail.php" class="ui-btn">Add Order Detail</a>
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order Detail ID</th>
                <th>Order ID</th>
                <th>Equipment</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = oci_fetch_assoc($res)): ?>
            <tr>
                <td><?= htmlspecialchars($row['ORDER_DETAIL_ID']) ?></td>
                <td><?= htmlspecialchars($row['ORDER_ID']) ?></td>
                <td><?= htmlspecialchars($row['EQUIPMENT_NAME']) ?></td>
                <td><?= htmlspecialchars($row['QUANTITY']) ?></td>
                <td><?= htmlspecialchars($row['PRICE']) ?></td>
                <td class="actions">
                    <a href="edit_order_detail.php?id=<?= $row['ORDER_DETAIL_ID'] ?>" class="ui-btn">Edit</a>
                    <a href="delete_order_detail.php?id=<?= $row['ORDER_DETAIL_ID'] ?>" class="ui-btn delete" onclick="return confirm('Are you sure to delete this record?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
