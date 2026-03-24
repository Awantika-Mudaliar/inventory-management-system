<?php
include 'db_connect.php';

$payment_id = $_GET['id'] ?? '';
if (!$payment_id) die('Invalid Payment ID');

$delStmt = oci_parse($conn, "DELETE FROM payments WHERE payment_id = :id");
oci_bind_by_name($delStmt, ':id', $payment_id);

if (oci_execute($delStmt)) {
    header("Location: view_payment.php");
    exit;
} else {
    echo "Failed to delete payment.";
}
?>
