<?php include 'db_connect.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $equipment_id = $_POST['equipment_id'];
    $stock_level = $_POST['stock_level'];

    $ins = oci_parse($conn, "INSERT INTO Inventory (equipment_id, stock_level) VALUES (:eid, :stock)");
    oci_bind_by_name($ins, ':eid', $equipment_id);
    oci_bind_by_name($ins, ':stock', $stock_level);
    $result = oci_execute($ins);
    if ($result) {
        echo "Inventory added successfully!";
    } else {
        echo "Error adding inventory!";
    }
}
?>

<h2>Add Inventory Stock</h2>

<form method="POST">
    Equipment: 
    <select name="equipment_id" required>
        <option value="">Select Equipment</option>
        <?php
        $res = oci_parse($conn, "SELECT equipment_id, equipment_name FROM Equipment ORDER BY equipment_name");
        oci_execute($res);
        while($row = oci_fetch_assoc($res)){
            echo "<option value='{$row['EQUIPMENT_ID']}'>{$row['EQUIPMENT_NAME']}</option>";
        }
        ?>
    </select>
    <br><br>
    Stock Level: <input type="number" name="stock_level" required min="0"><br><br>
    <button type="submit">Add Inventory</button>
</form>

<a href="view_inventory.php">View Inventory</a>
