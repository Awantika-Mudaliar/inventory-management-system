<?php
include 'db_connect.php';

$id = $_GET['id'] ?? null;
$order_id = $_GET['order_id'] ?? null; // Pass order_id for redirection (optional)

if ($id) {
    $del = oci_parse($conn, "DELETE FROM order_details WHERE order_detail_id = :id");
    oci_bind_by_name($del, ":id", $id);
    oci_execute($del);
}

// Redirect back to the order details page of the relevant order
if ($order_id) {
    header('Location: view_order_details.php?order_id=' . $order_id);
} else {
    header('Location: view_order_details.php');
}
exit;
