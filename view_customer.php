<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Customers</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .ui-button-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 22px;
      margin-bottom: 18px;
    }
    .ui-btn {
      background-color: #007bff;
      color: white;
      font-size: 1.18rem;
      font-family: "Times New Roman", Times, serif;
      border: none;
      border-radius: 10px;
      padding: 1.3em 0;
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
    }
    table.table {
      margin-top: 8px;
    }
  </style>
</head>
<body>
<div class="card">
  <div class="ui-button-grid">
    <a href="index.php" class="ui-btn">Back to Dashboard</a>
    <a href="add_customer.php" class="ui-btn">Add Customer</a>
  </div>
  <h2>Customers List</h2>
  <table class="table">
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
    <?php
    $stmt = oci_parse($conn, "SELECT * FROM Customer ORDER BY customer_id");
    oci_execute($stmt);
    while ($row = oci_fetch_assoc($stmt)) {
      echo "<tr>
              <td>{$row['CUSTOMER_ID']}</td>
              <td>" . htmlspecialchars($row['CNAME']) . "</td>
              <td>" . htmlspecialchars($row['ADDRESS']) . "</td>
              <td>" . htmlspecialchars($row['STATE']) . "</td>
              <td>{$row['PINCODE']}</td>
              <td>" . htmlspecialchars($row['EMAIL']) . "</td>
              <td>" . htmlspecialchars($row['PHONE_NO']) . "</td>
              <td>
                <a href='edit_customer.php?id={$row['CUSTOMER_ID']}' class='ui-btn' style='background:#16a085;'>Edit</a>
                <a href='delete_customer.php?id={$row['CUSTOMER_ID']}' class='ui-btn' style='background:#d9534f;'>Delete</a>
              </td>
            </tr>";
    }
    ?>
  </table>
</div>
</body>
</html>
