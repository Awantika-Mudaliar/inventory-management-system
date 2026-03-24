<?php
include 'db_connect.php';

$id = $_GET['id'] ?? '';
if (!$id) die('Invalid Customer ID');

$stmt = oci_parse($conn, "DELETE FROM Customer WHERE customer_id=:id");
oci_bind_by_name($stmt, ':id', $id);

if (oci_execute($stmt)) {
    header("Location: view_customer.php");
    exit;
} else {
    echo "Failed to delete customer.";
}
