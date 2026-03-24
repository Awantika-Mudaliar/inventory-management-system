<?php
include 'db_connect.php';

$id = $_GET['id'] ?? '';
if (!$id) die('Invalid Customer ID');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = oci_parse($conn, "UPDATE Customer SET cname=:name, address=:address, state=:state, pincode=:pincode, email=:email, phone_no=:phone WHERE customer_id=:id");
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':address', $address);
    oci_bind_by_name($stmt, ':state', $state);
    oci_bind_by_name($stmt, ':pincode', $pincode);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':phone', $phone);
    oci_bind_by_name($stmt, ':id', $id);

    if (oci_execute($stmt)) {
        $message = "Customer updated successfully!";
    } else {
        $message = "Update failed.";
    }
}

$stmt2 = oci_parse($conn, "SELECT * FROM Customer WHERE customer_id=:id");
oci_bind_by_name($stmt2, ':id', $id);
oci_execute($stmt2);
$row = oci_fetch_assoc($stmt2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<a href="view_customer.php" class="btn">Back to Customers List</a>
<h2>Edit Customer</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?=htmlspecialchars($row['CNAME'])?>" required>
    <label>Address</label>
    <input type="text" name="address" value="<?=htmlspecialchars($row['ADDRESS'])?>">
    <label>State</label>
    <input type="text" name="state" value="<?=htmlspecialchars($row['STATE'])?>">
    <label>Pincode</label>
    <input type="text" name="pincode" value="<?=htmlspecialchars($row['PINCODE'])?>">
    <label>Email</label>
    <input type="email" name="email" value="<?=htmlspecialchars($row['EMAIL'])?>">
    <label>Phone</label>
    <input type="text" name="phone" value="<?=htmlspecialchars($row['PHONE_NO'])?>">
    <br><br>
    <button type="submit" class="btn">Update Customer</button>
</form>
</div>
</body>
</html>
