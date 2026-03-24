<?php
include 'db_connect.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['supplier_name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $email = $_POST['email'];
    $equipment_ids = $_POST['equipment_ids'] ?? [];

    // Insert supplier
    $stmt = oci_parse($conn, "INSERT INTO Supplier (supplier_id, supplier_name, address, state, pincode, email) VALUES (supplier_seq.NEXTVAL, :name, :address, :state, :pincode, :email)");
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':address', $address);
    oci_bind_by_name($stmt, ':state', $state);
    oci_bind_by_name($stmt, ':pincode', $pincode);
    oci_bind_by_name($stmt, ':email', $email);
    $exec_result = oci_execute($stmt);

    if ($exec_result) {
        // Get current supplier sequence value
        $sid_query = oci_parse($conn, "SELECT supplier_seq.CURRVAL FROM dual");
        oci_execute($sid_query);
        $sid_row = oci_fetch_row($sid_query);
        $supplier_id = $sid_row[0];

        // Insert into Supplier_Equipment
        foreach ($equipment_ids as $eq_id) {
            $stmt2 = oci_parse($conn, "INSERT INTO Supplier_Equipment (supplier_id, equipment_id) VALUES (:sid, :eqid)");
            oci_bind_by_name($stmt2, ':sid', $supplier_id);
            oci_bind_by_name($stmt2, ':eqid', $eq_id);
            oci_execute($stmt2);
        }

        $message = "Supplier added successfully!";
    } else {
        $message = "Failed to add supplier.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        .card {
            width: 95vw;
            max-width: 1400px;
            min-height: 90vh;
            margin: 20px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
            font-family: "Times New Roman", Times, serif;
        }
        form label {
            display: block;
            font-weight: 600;
            color: #2866a4;
            margin-bottom: 6px;
        }
        form input[type="text"],
        form input[type="email"],
        form select {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #b2b2b2;
            font-family: "Times New Roman", Times, serif;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            border: none;
            display: inline-block;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            width: 100%;
        }
        .ui-btn:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 20px;
        }
        p.message {
            font-family: "Times New Roman", Times, serif;
            font-weight: 600;
            color: green;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="card">
    <a href="index.php" class="ui-btn" style="margin-bottom: 20px;">Back to Dashboard</a>
    <h2>Add New Supplier</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>
    <form method="post">
        <label>Supplier Name:</label>
        <input type="text" name="supplier_name" required>

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>State:</label>
        <input type="text" name="state" required>

        <label>Pincode:</label>
        <input type="text" name="pincode" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Equipments Supplied (Ctrl+Click for multi-select):</label>
        <select name="equipment_ids[]" multiple required>
            <?php
            $eq_query = oci_parse($conn, "SELECT equipment_id, equipment_name FROM Equipment ORDER BY equipment_name");
            oci_execute($eq_query);
            while ($eq = oci_fetch_assoc($eq_query)) {
                echo "<option value='{$eq['EQUIPMENT_ID']}'>" . htmlspecialchars($eq['EQUIPMENT_NAME']) . "</option>";
            }
            ?>
        </select>
        <button type="submit" class="ui-btn">Add Supplier</button>
    </form>
</div>
</body>
</html>
