<?php
include 'db_connect.php';
$res = oci_parse($conn, "SELECT EQUIPMENT_ID, EQUIPMENT_NAME, CATEGORY_NAME, QUANTITY, PRICE FROM EQUIPMENT");
oci_execute($res);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Equipment List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
            width: 95vw;
            max-width: 1200px;
            margin: 28px auto;
            border-radius: 14px;
            box-shadow: 0 4px 25px rgb(0 0 0 / .08);
            padding: 44px 36px;
        }
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 16px;
        }
        .ui-btn {
            background-color: #007bff;
            color: white;
            font-size: 1.15rem;
            font-family: "Times New Roman", Times, serif;
            border: none;
            border-radius: 10px;
            padding: 1.1em 0;
            font-weight: bold;
            width: 100%;
            text-align: center;
            transition: background-color 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            cursor: pointer;
            display: block;
        }
        .ui-btn:hover {
            background: #0056b3;
        }
        .card h2 {
            margin-bottom: 16px;
            text-align: center;
            font-size: 2rem;
            font-family: "Times New Roman", Times, serif;
        }
        table {
            margin-top:8px;
            width: 100%;
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 14px 15px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
      <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <h2>Equipment List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Price (₹)</th>
        </tr>
        <?php while($row = oci_fetch_assoc($res)): ?>
        <tr>
            <td><?= $row['EQUIPMENT_ID'] ?></td>
            <td><?= htmlspecialchars($row['EQUIPMENT_NAME']) ?></td>
            <td><?= htmlspecialchars($row['CATEGORY_NAME']) ?></td>
            <td><?= $row['QUANTITY'] ?></td>
            <td><?= $row['PRICE'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
