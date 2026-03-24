<?php include 'db_connect.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$del = oci_parse($conn, "DELETE FROM Store_Manager WHERE manager_id=:id");
oci_bind_by_name($del, ":id", $id);

if (oci_execute($del)) {
    echo "Store Manager deleted successfully!";
} else {
    echo "Error deleting Store Manager!";
}
?>
<br>
<a href="view_store_manager.php">Back to Store Managers</a>
