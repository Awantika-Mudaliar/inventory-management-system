<?php
include 'db_connect.php';

$stmt = oci_parse($conn, "SELECT c.customer_id, c.cname, NVL(SUM(s.total_amount), 0) AS total_sales
                          FROM Customer c LEFT JOIN shop_order s ON c.customer_id = s.customer_id
                          GROUP BY c.customer_id, c.cname ORDER BY total_sales DESC");
oci_execute($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Sales Summary</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
            width: 95vw;
            max-width: 1400px;
            min-height: 90vh;
            margin: 20px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
            font-family: "Times New Roman", Times, serif;
        }
        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 2rem;
        }
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .ui-btn {
            background-color: #007bff;
            color: white;
            font-weight: 700;
            font-family: "Times New Roman", Times, serif;
            font-size: 1.15rem;
            padding: 1.3em 0;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            border: none;
            display: block;
            transition: background-color 0.3s ease;
        }
        .ui-btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
            font-family: "Times New Roman", Times, serif;
        }
        th, td {
            padding: 14px 20px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <h2>Sales Summary by Customer</h2>
    <table>
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = oci_fetch_assoc($stmt)): ?>
                <tr>
                    <td><?= $row['CUSTOMER_ID'] ?></td>
                    <td><?= htmlspecialchars($row['CNAME']) ?></td>
                    <td>₹<?= $row['TOTAL_SALES'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
