<?php include 'db_connect.php';
$id = $_GET['id'];
$del = oci_parse($conn,"DELETE FROM Equipment WHERE equipment_id=:id");
oci_bind_by_name($del, ':id', $id); oci_execute($del);
echo "Deleted Successfully";
?>
