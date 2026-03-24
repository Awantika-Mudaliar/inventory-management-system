<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];
    $pay_date = $_POST['pay_date'];
    $pay_mode = $_POST['pay_mode'];

    // Insert payment
    $insertPayment = oci_parse($conn, "
        INSERT INTO payment (payment_id, order_id, amount, pay_date, pay_mode)
        VALUES (payment_seq.NEXTVAL, :order_id, :amount, TO_DATE(:pay_date, 'YYYY-MM-DD'), :pay_mode)
    ");
    oci_bind_by_name($insertPayment, ':order_id', $order_id);
    oci_bind_by_name($insertPayment, ':amount', $amount);
    oci_bind_by_name($insertPayment, ':pay_date', $pay_date);
    oci_bind_by_name($insertPayment, ':pay_mode', $pay_mode);

    $success = oci_execute($insertPayment);

    if ($success) {
        // Update store budget by adding payment amount
        $updateBudget = oci_parse($conn, "
            UPDATE store_budget 
            SET current_balance = current_balance + :amount
        ");
        oci_bind_by_name($updateBudget, ':amount', $amount);
        oci_execute($updateBudget);

        oci_commit($conn);  // commit transaction

        echo "<p style='color:green;'>Payment recorded and store budget updated successfully.</p>";
    } else {
        $e = oci_error($insertPayment);
        echo "<p style='color:red;'>Error recording payment: " . htmlspecialchars($e['message']) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Payment</title>
</head>
<body>
<h2>Add Manual Payment</h2>
<form method="POST">
    <label for="order_id">Order ID:</label><br>
    <input type="number" name="order_id" id="order_id" required><br><br>

    <label for="amount">Amount:</label><br>
    <input type="number" step="0.01" name="amount" id="amount" required><br><br>

    <label for="pay_date">Payment Date:</label><br>
    <input type="date" name="pay_date" id="pay_date" required><br><br>

    <label for="pay_mode">Payment Mode:</label><br>
    <input type="text" name="pay_mode" id="pay_mode" required><br><br>

    <button type="submit">Add Payment</button>
</form>
<a href="view_payment.php">Back to Payments List</a>
</body>
</html>
