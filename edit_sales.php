<?php include 'db_connect.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = oci_parse($conn, "SELECT * FROM Sales WHERE sales_id = :id");
oci_bind_by_name($query, ":id", $id);
oci_execute($query);
$data = oci_fetch_assoc($query);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $order_id = $_POST['order_id'];
    $equipment_id = $_POST['equipment_id'];
    $sold_quantity = $_POST['sold_quantity'];
    $sale_date = $_POST['sale_date'];

    $upd = oci_parse($conn, "UPDATE Sales SET order_id=:oid, equipment_id=:eid, sold_quantity=:sq, sale_date=TO_DATE(:sdate, 'YYYY-MM-DD') WHERE sales_id=:id");
    oci_bind_by_name($upd, ":oid", $order_id);
    oci_bind_by_name($upd, ":eid", $equipment_id);
    oci_bind_by_name($upd, ":sq", $sold_quantity);
    oci_bind_by_name($upd, ":sdate", $sale_date);
    oci_bind_by_name($upd, ":id", $id);

    if(oci_execute($upd)){
        echo "Sales record updated successfully!";
    } else {
        echo "Error updating sales record!";
    }
    oci_execute($query);
    $data = oci_fetch_assoc($query);
}
?>

<h2>Edit Sales Record</h2>

<form method="POST">
    Order:
    <select name="order_id" required>
        <option value="">Select Order</option>
        <?php
        $res = oci_parse($conn, "SELECT order_id FROM Shop_Order ORDER BY order_id DESC");
        oci_execute($res);
        while($row = oci_fetch_assoc($res)){
            $selected = ($row['ORDER_ID'] == $data['ORDER_ID']) ? "selected" : "";
            echo "<option value='{$row['ORDER_ID']}' $selected>{$row['ORDER_ID']}</option>";
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
            $selected = ($row['EQUIPMENT_ID'] == $data['EQUIPMENT_ID']) ? "selected" : "";
            echo "<option value='{$row['EQUIPMENT_ID']}' $selected>{$row['EQUIPMENT_NAME']}</option>";
        }
        ?>
    </select><br><br>

    Sold Quantity: <input type="number" name="sold_quantity" min="1" required value="<?= $data['SOLD_QUANTITY'] ?>"><br><br>
    Sale Date: <input type="date" name="sale_date" required value="<?= $data['SALE_DATE'] ?>"><br><br>

    <button type="submit">Update Sales Record</button>
</form>

<a href="view_sales.php">Back to Sales</a>
