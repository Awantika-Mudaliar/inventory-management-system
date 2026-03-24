<?php
include 'db_connect.php';

$payment_id = $_GET['id'] ?? '';
if (!$payment_id) die('Invalid Payment ID');

$message = '';

$stmt = oci_parse($conn, "SELECT * FROM payments WHERE payment_id = :id");
oci_bind_by_name($stmt, ':id', $payment_id);
oci_execute($stmt);
$payment = oci_fetch_assoc($stmt);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $amount = $_POST['amount'];
    $pay_date = $_POST['pay_date'];
    $pay_mode = $_POST['pay_mode'];

    $updStmt = oci_parse($conn, "UPDATE payments SET order_id = :oid, amount = :amount, pay_date = TO_DATE(:pay_date,'YYYY-MM-DD'), pay_mode = :pay_mode WHERE payment_id = :pid");
    oci_bind_by_name($updStmt, ':oid', $order_id);
    oci_bind_by_name($updStmt, ':amount', $amount);
    oci_bind_by_name($updStmt, ':pay_date', $pay_date);
    oci_bind_by_name($updStmt, ':pay_mode', $pay_mode);
    oci_bind_by_name($updStmt, ':pid', $payment_id);

    if (oci_execute($updStmt)) {
        $message = "Payment updated successfully";
        header("Location: view_payment.php");
        exit;
    } else {
        $message = "Payment update failed";
    }
}

// Fetch orders list
$orderStmt = oci_parse($conn, "SELECT order_id FROM shop_order ORDER BY order_date DESC");
oci_execute($orderStmt);
?>

<!DOCTYPE html>
<html>
<head><title>Edit Payment</title></head>
<body>
<div class="card">
<a href="view_payment.php" class="btn">Back to Payments</a>
<h2>Edit Payment</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    <label>Order ID</label>
    <select name="order_id" required>
        <?php while ($order = oci_fetch_assoc($orderStmt)) { ?>
            <option value="<?= $order['ORDER_ID']?>" <?= ($payment['ORDER_ID'] == $order['ORDER_ID']) ? 'selected' : '' ?>>
                <?= $order['ORDER_ID'] ?>
            </option>
        <?php } ?>
    </select>

    <label>Amount</label>
    <input type="number" step="0.01" name="amount" value="<?= $payment['AMOUNT'] ?>" required>

    <label>Payment Date</label>
    <input type="date" name="pay_date" value="<?= date('Y-m-d', strtotime($payment['PAY_DATE'])) ?>" required>

    <label>Payment Mode</label>
    <input type="text" name="pay_mode" value="<?= htmlspecialchars($payment['PAY_MODE']) ?>" required>

    <button type="submit" class="btn">Update Payment</button>
</form>
</div>
</body>
</html>
