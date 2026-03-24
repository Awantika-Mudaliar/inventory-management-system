<?php
include 'db_connect.php';

$message = '';
$name = $address = $state = $pincode = $email = $phone = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use isset() to check if each POST data is set
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $state = isset($_POST['state']) ? $_POST['state'] : '';
    $pincode = isset($_POST['pincode']) ? $_POST['pincode'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

    // Proceed with insert
    $stmt = oci_parse($conn, "INSERT INTO Customer (customer_id, cname, address, state, pincode, email, phone_no)
        VALUES (customer_seq.NEXTVAL, :name, :address, :state, :pincode, :email, :phone)");
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':address', $address);
    oci_bind_by_name($stmt, ':state', $state);
    oci_bind_by_name($stmt, ':pincode', $pincode);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':phone', $phone);
    if (oci_execute($stmt)) {
        $message = "Customer added successfully.";
    } else {
        $message = "Failed to add customer.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<a href="view_customer.php" class="btn">Back to Customers list</a>
<h2>Add Customer</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post">
    <label>Name</label>
    <input type="text" name="name" required>

    <label>Address</label>
    <input type="text" name="address">

    <label>State</label>
    <input type="text" name="state">

    <label>Pincode</label>
    <input type="text" name="pincode">

    <label>Email</label>
    <input type="email" name="email">

    <label>Phone</label>
    <input type="text" name="phone">

    <br><br>
    <button type="submit" class="btn">Add Customer</button>
</form>
</div>
</body>
</html>
