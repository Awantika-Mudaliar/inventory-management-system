<?php
include 'db_connect.php';

// Fetch stock levels below 10
$res_low_stock = oci_parse($conn, "SELECT equipment_id, equipment_name, stock_level FROM Equipment WHERE stock_level < 10");
oci_execute($res_low_stock);
$lowStock = [];
while ($row = oci_fetch_assoc($res_low_stock)) {
    $lowStock[] = $row;
}

// Budget calculation (assuming a total budget value; for now, let's say 10000)
$total_budget = 10000;

// Calculate total inventory value (dummy calculation: sum of stock * 100 for simplicity)
$res_total_value = oci_parse($conn, "SELECT SUM(stock_level * 100) AS total_value FROM Equipment");
oci_execute($res_total_value);
$row_value = oci_fetch_assoc($res_total_value);
$current_value = $row_value['TOTAL_VALUE'] ?? 0;

$budgetMessage = "";
if ($current_value > $total_budget) {
    $budgetMessage = "Budget exceeded! Inventory value: $$current_value exceeds budget: $$total_budget.";
} else {
    $remaining = $total_budget - $current_value;
    $budgetMessage = "Budget OK. Remaining budget: $$remaining.";
}

// Send data as JSON
echo json_encode([
    'lowStock' => $lowStock,
    'budgetMessage' => $budgetMessage
]);
?>
