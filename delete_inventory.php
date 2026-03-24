<?php include 'db_connect.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$del = oci_parse($conn, "DELETE FROM Inventory WHERE equipment_id = :id");
oci_bind_by_name($del, ":id", $id);

if (oci_execute($del)) {
    echo "Inventory deleted successfully!";
} else {
    echo "Error deleting Inventory!";
}
?>
<br>
<a href="view_inventory.php">Back to Inventory</a>
