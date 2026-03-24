<?php
include 'db_connect.php';

$message = '';

// Fetch customers
$customers = [];
$custStmt = oci_parse($conn, "SELECT customer_id, cname FROM Customer ORDER BY cname");
oci_execute($custStmt);
while ($row = oci_fetch_assoc($custStmt)) {
    $customers[] = $row;
}

// Fetch equipment
$equipments = [];
$equipStmt = oci_parse($conn, "SELECT equipment_id, equipment_name, price FROM Equipment ORDER BY equipment_name");
oci_execute($equipStmt);
while ($row = oci_fetch_assoc($equipStmt)) {
    $equipments[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $order_date = $_POST['order_date'];
    $order_items = $_POST['order_items']; // array of ['equipment_id'=>..., 'quantity'=>...]

    // Calculate total amount
    $total_amount = 0;
    foreach ($order_items as $item) {
        $priceStmt = oci_parse($conn, "SELECT price FROM Equipment WHERE equipment_id = :eid");
        oci_bind_by_name($priceStmt, ':eid', $item['equipment_id']);
        oci_execute($priceStmt);
        $priceRow = oci_fetch_assoc($priceStmt);
        $price = ($priceRow && isset($priceRow['PRICE'])) ? $priceRow['PRICE'] : 0;
        $total_amount += $price * $item['quantity'];
    }

    // Insert order
    $orderStmt = oci_parse(
        $conn,
        "INSERT INTO shop_order (order_id, customer_id, order_date, total_amount) VALUES (order_seq.NEXTVAL, :cust, TO_DATE(:odate,'YYYY-MM-DD'), :tamount)"
    );
    oci_bind_by_name($orderStmt, ':cust', $customer_id);
    oci_bind_by_name($orderStmt, ':odate', $order_date);
    oci_bind_by_name($orderStmt, ':tamount', $total_amount);

    if (oci_execute($orderStmt, OCI_NO_AUTO_COMMIT)) {
        // Get the new order_id
        $seqStmt = oci_parse($conn, "SELECT order_seq.CURRVAL AS last_id FROM dual");
        oci_execute($seqStmt);
        $seqRow = oci_fetch_assoc($seqStmt);
        $order_id = $seqRow['LAST_ID'];

        $allOk = true;
        foreach ($order_items as $item) {
            $equip_id = $item['equipment_id'];
            $qty = $item['quantity'];

            // Get price
            $priceStmt = oci_parse($conn, "SELECT price FROM Equipment WHERE equipment_id = :eid");
            oci_bind_by_name($priceStmt, ':eid', $equip_id);
            oci_execute($priceStmt);
            $priceRow = oci_fetch_assoc($priceStmt);
            $price = ($priceRow && isset($priceRow['PRICE'])) ? $priceRow['PRICE'] : 0;

            // Insert into Order_Details
            $itemStmt = oci_parse(
                $conn,
                "INSERT INTO Order_Details (order_detail_id, order_id, equipment_id, quantity, price) VALUES (order_details_seq.NEXTVAL, :oid, :eid, :qty, :pr)"
            );
            oci_bind_by_name($itemStmt, ':oid', $order_id);
            oci_bind_by_name($itemStmt, ':eid', $equip_id);
            oci_bind_by_name($itemStmt, ':qty', $qty);
            oci_bind_by_name($itemStmt, ':pr', $price);

            if (!oci_execute($itemStmt)) {
                $allOk = false;
                break;
            }

            // Update Inventory stock level
            $invStmt = oci_parse(
                $conn,
                "UPDATE Inventory SET stock_level = stock_level - :qty WHERE equipment_id = :eid"
            );
            oci_bind_by_name($invStmt, ':qty', $qty);
            oci_bind_by_name($invStmt, ':eid', $equip_id);

            if (!oci_execute($invStmt)) {
                $allOk = false;
                break;
            }
        }

        if ($allOk) {
            oci_commit($conn);
            $message = "Order added successfully.";
        } else {
            oci_rollback($conn);
            $message = "Error inserting order details or updating inventory.";
        }
    } else {
        oci_rollback($conn);
        $message = "Failed to add order.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Order</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<div class="card">
    <a href="view_orders.php" class="btn">Back to Orders</a>
    <h2>Add Order</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" id="orderForm">
        <label for="customer_id">Customer</label>
        <select name="customer_id" id="customer_id" required>
            <option value="">Select Customer</option>
            <?php foreach ($customers as $cust): ?>
                <option value="<?= htmlspecialchars($cust['CUSTOMER_ID']) ?>"><?= htmlspecialchars($cust['CNAME']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="order_date">Order Date</label>
        <input type="date" name="order_date" id="order_date" required value="<?= date('Y-m-d') ?>">

        <h3>Order Items</h3>
        <div id="itemsContainer">
            <div class="itemRow">
                <select name="order_items[0][equipment_id]" required>
                    <option value="">Select Equipment</option>
                    <?php foreach ($equipments as $equip): ?>
                        <option value="<?= htmlspecialchars($equip['EQUIPMENT_ID']) ?>"><?= htmlspecialchars($equip['EQUIPMENT_NAME']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="order_items[0][quantity]" min="1" value="1" required>
                <button type="button" onclick="removeItemRow(this)">Remove</button>
            </div>
        </div>

        <button type="button" onclick="addItemRow()">Add Another Item</button>
        <br><br>
        <button type="submit">Submit Order</button>
    </form>
</div>

<script>
let itemIndex = 1;
function addItemRow() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.classList.add('itemRow');
    newRow.innerHTML = `
        <select name="order_items[${itemIndex}][equipment_id]" required>
            <option value="">Select Equipment</option>
            <?php foreach ($equipments as $equip): ?>
                <option value="<?= htmlspecialchars($equip['EQUIPMENT_ID']) ?>"><?= htmlspecialchars($equip['EQUIPMENT_NAME']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="order_items[${itemIndex}][quantity]" min="1" value="1" required>
        <button type="button" onclick="removeItemRow(this)">Remove</button>
    `;
    container.appendChild(newRow);
    itemIndex++;
}

function removeItemRow(button) {
    button.parentElement.remove();
}
</script>
</body>
</html>
