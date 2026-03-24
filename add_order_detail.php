<?php include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $order_id = $_POST['order_id'];
    $equipment_id = $_POST['equipment_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $ins = oci_parse($conn, "INSERT INTO Order_Details (order_id, equipment_id, quantity, price) VALUES (:oid, :eid, :qty, :pr)");
    oci_bind_by_name($ins, ':oid', $order_id);
    oci_bind_by_name($ins, ':eid', $equipment_id);
    oci_bind_by_name($ins, ':qty', $quantity);
    oci_bind_by_name($ins, ':pr', $price);
    if(oci_execute($ins)){
        echo "Order Detail added successfully!";
    } else {
        echo "Error adding order detail!";
    }
}
?>

<h2>Add Order Detail</h2>

<form method="POST">
    Order:
    <select name="order_id" required>
        <option value="">Select Order</option>
        <?php
        $res = oci_parse($conn, "SELECT order_id FROM Shop_Order ORDER BY order_id DESC");
        oci_execute($res);
        while($row = oci_fetch_assoc($res)){
            echo "<option value='{$row['ORDER_ID']}'>{$row['ORDER_ID']}</option>";
        }
        ?>
    </select><br><br>
    
    Equipment:
    <select name="equipment_id" required>
        <option value="">Select Equipment</option>
        <?php
        $res = oci_parse($conn, "SELECT equipment_id, equipment_name FROM Equipment ORDER BY equipment_name");
        oci_execute($res);
        while($row = oci_fetch_assoc($res)){
            echo "<option value='{$row['EQUIPMENT_ID']}'>{$row['EQUIPMENT_NAME']}</option>";
        }
        ?>
    </select><br><br>

    Quantity: <input type="number" name="quantity" required min="1"><br><br>
    Price: <input type="number" step="0.01" name="price" required><br><br>
    <button type="submit">Add Order Detail</button>
</form>

<a href="view_order_details.php">View Order Details</a>
