<?php
include 'db_connect.php';
$stmt = oci_parse($conn, "SELECT * FROM store_Manager ORDER BY manager_id");
oci_execute($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Store Managers</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .card {
            width: 95vw;
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            border-radius: 14px;
            padding: 44px 36px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            font-family: "Times New Roman", Times, serif;
        }
        h2 {
            text-align: center;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 24px;
        }
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .ui-btn {
    background-color: #007bff !important; /* Normal blue */
    color: #fff !important;
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
    background-color: #0056b3 !important; /* Darker blue on hover */
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
        .actions a {
            margin-right: 8px;
            padding: 8px 14px;
            font-family: "Times New Roman", Times, serif;
            font-weight: 600;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
        .actions a.edit {
            background-color: #198754;
        }
        .actions a.delete {
            background-color: #d9534f;
        }
        .actions a:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
      <a href="index.php" class="ui-btn">Back to Dashboard</a>
      <a href="add_store_manager.php" class="ui-btn">Add Store Manager</a>
    </div>
    <h2>Store Managers</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>State</th>
                <th>Pincode</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = oci_fetch_assoc($stmt)): ?>
            <tr>
                <td><?= $row['MANAGER_ID'] ?></td>
                <td><?= htmlspecialchars($row['CNAME']) ?></td>
                <td><?= htmlspecialchars($row['ADDRESS']) ?></td>
                <td><?= htmlspecialchars($row['STATE']) ?></td>
                <td><?= $row['PINCODE'] ?></td>
                <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                <td><?= htmlspecialchars($row['PHONE']) ?></td>
                <td class="actions">
                    <a href="edit_store_manager.php?id=<?= $row['MANAGER_ID'] ?>" class="edit">Edit</a>
                    <a href="delete_store_manager.php?id=<?= $row['MANAGER_ID'] ?>" class="delete" onclick="return confirm('Are you sure to delete this record?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
