<?php
include 'db_connect.php';

$supplier_id = $_GET['id'] ?? '';

if ($supplier_id) {
    // Delete from supplier_equipment first
    $del_eq = oci_parse($conn, "DELETE FROM Supplier_Equipment WHERE supplier_id = :supp_id");
    oci_bind_by_name($del_eq, ':supp_id', $supplier_id);
    oci_execute($del_eq);

    // Delete supplier
    $del_supp = oci_parse($conn, "DELETE FROM Supplier WHERE supplier_id = :supp_id");
    oci_bind_by_name($del_supp, ':supp_id', $supplier_id);
    if (oci_execute($del_supp)) {
        header("Location: view_supplier.php");
        exit;
    } else {
        echo "Failed to delete supplier";
    }
} else {
    echo "Supplier ID missing";
}
?>
