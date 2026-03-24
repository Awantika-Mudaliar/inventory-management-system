<?php
include 'db_connect.php';
$stmt = oci_parse($conn, "SELECT e.equipment_id, e.equipment_name, NVL(i.stock_level, 0) AS stock_level
                          FROM Equipment e LEFT JOIN Inventory i ON e.equipment_id = i.equipment_id
                          ORDER BY e.equipment_name");
oci_execute($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Status</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
            width: 95vw;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            padding: 40px 36px;
        }
        .ui-button-grid {
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
        }
        .ui-btn {
            background-color: #007bff;
            color: white;
            font-family: "Times New Roman", Times, serif;
            font-weight: 700;
            font-size: 1.2rem;
            border: none;
            border-radius: 10px;
            padding: 1.3em 0;
            width: 100%;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
        }
        .ui-btn:hover {
            background-color: #0056b3;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: "Times New Roman", Times, serif;
            font-size: 17px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 14px 18px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: 700;
        }
        h2 {
            font-family: "Times New Roman", Times, serif;
            text-align: center;
            font-weight: 700;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <h2>Inventory Status</h2>
    <table>
        <tr>
            <th>Equipment ID</th>
            <th>Equipment Name</th>
            <th>Stock Level</th>
        </tr>
        <?php while ($row = oci_fetch_assoc($stmt)): ?>
        <tr>
            <td><?= $row['EQUIPMENT_ID'] ?></td>
            <td><?= htmlspecialchars($row['EQUIPMENT_NAME']) ?></td>
            <td><?= $row['STOCK_LEVEL'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
