<?php
include 'db_connect.php';

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $pincode = $_POST['pincode'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = oci_parse($conn, "INSERT INTO store_Manager (manager_id, cname, address, state, pincode, email, phone)
        VALUES (manager_seq.NEXTVAL, :name, :address, :state, :pincode, :email, :phone)");
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':address', $address);
    oci_bind_by_name($stmt, ':state', $state);
    oci_bind_by_name($stmt, ':pincode', $pincode);
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':phone', $phone);
    if(oci_execute($stmt)){
        $message = "Store Manager added successfully!";
    } else {
        $message = "Failed to add Store Manager.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Store Manager</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
<a href="index.php" class="btn">Back to Dashboard</a>
<h2>Add Store Manager</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form method="post">
    <label>Name</label>
    <input type="text" name="name" required>
    <label>Address</label>
    <input type="text" name="address" required>
    <label>State</label>
    <input type="text" name="state" required>
    <label>Pincode</label>
    <input type="text" name="pincode" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Phone</label>
    <input type="text" name="phone" required>
    <br><br>
    <button type="submit" class="btn">Add Manager</button>
</form>
</div>
</body>
</html>
