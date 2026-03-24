<?php include 'db_connect.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = oci_parse($conn, "SELECT * FROM Inventory WHERE equipment_id = :id");
oci_bind_by_name($query, ":id", $id);
oci_execute($query);
$data = oci_fetch_assoc($query);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $stock_level = $_POST['stock_level'];

    $upd = oci_parse($conn, "UPDATE Inventory SET stock_level = :stock WHERE equipment_id = :id");
    oci_bind_by_name($upd, ":stock", $stock_level);
    oci_bind_by_name($upd, ":id", $id);

    if(oci_execute($upd)){
        echo "Inventory updated successfully!";
    } else {
        echo "Error updating inventory!";
    }
    oci_execute($query);
    $data = oci_fetch_assoc($query);
}
?>

<h2>Edit Inventory</h2>

Equipment ID: <?= $id ?><br><br>

<form method="POST">
    Stock Level: <input type="number" name="stock_level" required min="0" value="<?= $data['STOCK_LEVEL'] ?>"><br><br>
    <button type="submit">Update Inventory</button>
</form>

<a href="view_inventory.php">Back to Inventory</a>
