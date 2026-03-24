<?php include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"]=="POST") {
    $order_id = $_POST['order_id'];
    $equipment_id = $_POST['equipment_id'];
    $sold_quantity = $_POST['sold_quantity'];
    $sale_date = $_POST['sale_date'];

    $ins = oci_parse($conn, "INSERT INTO Sales (order_id, equipment_id, sold_quantity, sale_date) VALUES (:oid, :eid, :sq, TO_DATE(:sdate, 'YYYY-MM-DD'))");
    oci_bind_by_name($ins, ':oid', $order_id);
    oci_bind_by_name($ins, ':eid', $equipment_id);
    oci_bind_by_name($ins, ':sq', $sold_quantity);
    oci_bind_by_name($ins, ':sdate', $sale_date);

    if (oci_execute($ins)) {
        echo "Sales record added successfully!";
    } else {
        echo "Error adding sales record!";
    }
}
?>

<h2>Add Sales Record</h2>

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

    Sold Quantity: <input type="number" name="sold_quantity" min="1" required><br><br>

    Sale Date: <input type="date" name="sale_date" required><br><br>

    <button type="submit">Add Sales Record</button>
</form>

<a href="view_sales.php">View Sales Records</a>
