<?php include 'db_connect.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$del = oci_parse($conn, "DELETE FROM Sales WHERE sales_id=:id");
oci_bind_by_name($del, ":id", $id);

if (oci_execute($del)) {
    echo "Sales record deleted successfully!";
} else {
    echo "Error deleting sales record!";
}
?>
<br>
<a href="view_sales.php">Back to Sales</a>
