<?php
include 'db_connect.php';

$order_id = $_GET['id'] ?? '';
if (!$order_id) die('Invalid Order ID');

// Delete related order_items first (if applicable)
$delItems = oci_parse($conn, "DELETE FROM order_items WHERE order_id = :id");
oci_bind_by_name($delItems, ':id', $order_id);
oci_execute($delItems);

// Delete order record
$delOrder = oci_parse($conn, "DELETE FROM shop_order WHERE order_id = :id");
oci_bind_by_name($delOrder, ':id', $order_id);

if (oci_execute($delOrder)) {
    header("Location: view_orders.php");
    exit;
} else {
    echo "Failed to delete order.";
}
?>
