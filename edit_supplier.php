<?php
include 'db_connect.php';

$supplier_id = $_GET['id'] ?? '';

if (!$supplier_id) {
    echo "Supplier ID missing.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update logic
    $name = $_POST['supplier_name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $email = $_POST['email'];
    $equipment_ids = $_POST['equipment_ids'] ?? [];

    $update = oci_parse($conn, "UPDATE Supplier SET supplier_name=:name, address=:address, state=:state, pincode=:pincode, email=:email WHERE supplier_id = :supp_id");
    oci_bind_by_name($update, ':name', $name);
    oci_bind_by_name($update, ':address', $address);
    oci_bind_by_name($update, ':state', $state);
    oci_bind_by_name($update, ':pincode', $pincode);
    oci_bind_by_name($update, ':email', $email);
    oci_bind_by_name($update, ':supp_id', $supplier_id);
    oci_execute($update);

    // Update Supplier_Equipment: Delete old mappings
    $del = oci_parse($conn, "DELETE FROM Supplier_Equipment WHERE supplier_id = :supp_id");
    oci_bind_by_name($del, ':supp_id', $supplier_id);
    oci_execute($del);

    // Insert new mappings
    foreach ($equipment_ids as $eq_id) {
        $ins = oci_parse($conn, "INSERT INTO Supplier_Equipment (supplier_id, equipment_id) VALUES (:supp_id, :eq_id)");
        oci_bind_by_name($ins, ':supp_id', $supplier_id);
        oci_bind_by_name($ins, ':eq_id', $eq_id);
        oci_execute($ins);
    }

    echo "<p>Supplier updated successfully.</p>";
}

// Fetch current data
$q = oci_parse($conn, "SELECT * FROM Supplier WHERE supplier_id = :supp_id");
oci_bind_by_name($q, ':supp_id', $supplier_id);
oci_execute($q);
$row = oci_fetch_assoc($q);

// Fetch currently linked equipment ids
$q2 = oci_parse($conn, "SELECT equipment_id FROM Supplier_Equipment WHERE supplier_id = :supp_id");
oci_bind_by_name($q2, ':supp_id', $supplier_id);
oci_execute($q2);
$current_eq = [];
while ($eq = oci_fetch_assoc($q2)) {
    $current_eq[] = $eq['EQUIPMENT_ID'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<a href="view_supplier.php" class="btn" style="margin-bottom:15px; background:#2866a4; color:#fff;">Back to Supplier List</a>
<h2>Edit Supplier</h2>
<form method="post">
    <label>Name:</label>
    <input type="text" name="supplier_name" value="<?=htmlspecialchars($row['SUPPLIER_NAME'])?>" required>
    <label>Address:</label>
    <input type="text" name="address" value="<?=htmlspecialchars($row['ADDRESS'])?>" required>
    <label>State:</label>
    <input type="text" name="state" value="<?=htmlspecialchars($row['STATE'])?>" required>
    <label>Pincode:</label>
    <input type="text" name="pincode" value="<?=htmlspecialchars($row['PINCODE'])?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?=htmlspecialchars($row['EMAIL'])?>" required>
    <label>Equipment Supplied (multi select):</label>
    <select name="equipment_ids[]" multiple required>
        <?php
        $eqs = oci_parse($conn, "SELECT equipment_id, equipment_name FROM Equipment ORDER BY equipment_name");
        oci_execute($eqs);
        while ($equip = oci_fetch_assoc($eqs)) {
            $selected = in_array($equip['EQUIPMENT_ID'], $current_eq) ? 'selected' : '';
            echo "<option value='{$equip['EQUIPMENT_ID']}' $selected>" . htmlspecialchars($equip['EQUIPMENT_NAME']) . "</option>";
        }
        ?>
    </select>
    <br><br>
    <button type="submit" class="btn">Update Supplier</button>
</form>
</div>
</body>
</html>
