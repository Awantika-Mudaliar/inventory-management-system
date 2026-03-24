<?php
include 'db_connect.php';

$query = "
  SELECT s.supplier_id, s.supplier_name, s.address, s.state, s.pincode, s.email,
         LISTAGG(e.equipment_name, ', ') WITHIN GROUP (ORDER BY e.equipment_name) AS equipments
  FROM Supplier s
  LEFT JOIN Supplier_Equipment se ON s.supplier_id = se.supplier_id
  LEFT JOIN Equipment e ON se.equipment_id = e.equipment_id
  GROUP BY s.supplier_id, s.supplier_name, s.address, s.state, s.pincode, s.email
  ORDER BY s.supplier_name
";

$stmt = oci_parse($conn, $query);
oci_execute($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Suppliers List</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
  width: 95vw;
  max-width: 1400px;
  min-height: 90vh; /* if relevant */
  margin: 20px auto;
  padding: 30px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 20px;
  overflow-y: auto;
}

        h2 {
            text-align: center;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
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
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 16px;
        }
        .ui-btn {
            background-color: #2866a4;
            color: white;
            font-family: "Times New Roman", Times, serif;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 1.3em 0;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            border: none;
            display: block;
            transition: background-color 0.3s ease;
        }
        .ui-btn:hover {
            background-color: #1f4e7a;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <h2>Suppliers</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>State</th>
                <th>Pincode</th>
                <th>Email</th>
                <th>Equipment Supplied</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = oci_fetch_assoc($stmt)): ?>
            <tr>
                <td><?= $row['SUPPLIER_ID'] ?></td>
                <td><?= htmlspecialchars($row['SUPPLIER_NAME']) ?></td>
                <td><?= htmlspecialchars($row['ADDRESS']) ?></td>
                <td><?= htmlspecialchars($row['STATE']) ?></td>
                <td><?= $row['PINCODE'] ?></td>
                <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                <td><?= htmlspecialchars($row['EQUIPMENTS']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
