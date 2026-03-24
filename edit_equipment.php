<?php include 'db_connect.php'; 
$id = $_GET['id'];
$query = oci_parse($conn,"SELECT * FROM Equipment WHERE equipment_id=:id");
oci_bind_by_name($query, ":id", $id);
oci_execute($query); $data = oci_fetch_assoc($query);
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = $_POST['equipment_name'];
    $category = $_POST['category_name'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];
    $upd = oci_parse($conn, "UPDATE Equipment SET equipment_name=:en, category_name=:cat, price=:pr, quantity=:qty WHERE equipment_id=:id");
    oci_bind_by_name($upd, ":en", $name);
    oci_bind_by_name($upd, ":cat", $category);
    oci_bind_by_name($upd, ":pr", $price);
    oci_bind_by_name($upd, ":qty", $qty);
    oci_bind_by_name($upd, ":id", $id);
    oci_execute($upd); echo "Updated successfully!"; }
?>
<form method="POST">
    Equipment Name: <input type="text" name="equipment_name" value="<?= $data['EQUIPMENT_NAME'] ?>"><br>
    Category Name: <input type="text" name="category_name" value="<?= $data['CATEGORY_NAME'] ?>"><br>
    Price: <input type="text" name="price" value="<?= $data['PRICE'] ?>"><br>
    Quantity: <input type="text" name="quantity" value="<?= $data['QUANTITY'] ?>"><br>
    <button type="submit">Update Equipment</button>
</form>
