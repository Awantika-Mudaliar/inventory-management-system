<?php
$conn = oci_connect('system', 'student', 'localhost/XE');
if (!$conn) {
    $e = oci_error();
    die("Connection failed: " . $e['message']);
}
?>
