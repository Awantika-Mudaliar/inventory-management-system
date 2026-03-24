<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? '';
    $payment_mode = $_POST['payment_mode'] ?? '';

    if (empty($payment_mode)) {
        echo "<p style='color:red; text-align:center;'>Please select a payment mode.</p>";
        echo "<p><a href='finalize_order_form.php?order_id=" . htmlspecialchars($order_id) . "'>Go back to finalization form</a></p>";
        exit;
    }

    // Check if sale already exists for this order
    $check = oci_parse($conn, "SELECT COUNT(*) AS COUNT FROM sales WHERE order_id = :order_id");
    oci_bind_by_name($check, ':order_id', $order_id);
    oci_execute($check);
    $count_row = oci_fetch_assoc($check);

    if ($count_row['COUNT'] > 0) {
        echo "<p style='color:red; text-align:center;'>This order has already been finalized.</p>";
        header("refresh:2;url=view_orders.php");
        exit;
    }

    // Insert sale record
    $insertSale = oci_parse($conn, "INSERT INTO sales (sales_id, order_id, sale_date) VALUES (sales_seq.NEXTVAL, :order_id, SYSDATE)");
    oci_bind_by_name($insertSale, ':order_id', $order_id);
    $saleResult = oci_execute($insertSale);

    // Get order total amount
    $res = oci_parse($conn, "SELECT total_amount FROM shop_order WHERE order_id = :order_id");
    oci_bind_by_name($res, ':order_id', $order_id);
    oci_execute($res);
    $row = oci_fetch_assoc($res);
    $total_amount = $row['TOTAL_AMOUNT'] ?? 0;

    // Insert payment record with manual payment mode
    $insertPayment = oci_parse($conn,
        "INSERT INTO payment (payment_id, order_id, amount, pay_date, pay_mode) VALUES (payment_seq.NEXTVAL, :order_id, :amount, SYSDATE, :pay_mode)"
    );
    oci_bind_by_name($insertPayment, ':order_id', $order_id);
    oci_bind_by_name($insertPayment, ':amount', $total_amount);
    oci_bind_by_name($insertPayment, ':pay_mode', $payment_mode);
    $paymentResult = oci_execute($insertPayment);

    // Update order status to finalized
    $updateOrder = oci_parse($conn, "UPDATE shop_order SET status = 'Finalized' WHERE order_id = :order_id");
    oci_bind_by_name($updateOrder, ':order_id', $order_id);
    $updateResult = oci_execute($updateOrder);

    if ($saleResult && $paymentResult && $updateResult) {
        oci_commit($conn);
        echo "<p style='color:green; text-align:center;'>Order finalized successfully.</p>";
    } else {
        oci_rollback($conn);
        echo "<p style='color:red; text-align:center;'>Failed to finalize the order.</p>";
    }

    header("refresh:2;url=view_orders.php");
    exit;
} else {
    echo "<p style='color:red; text-align:center;'>Invalid request method.</p>";
    header("refresh:2;url=view_orders.php");
    exit;
}
