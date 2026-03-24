<?php
include "db_connect.php";
$income_stmt = oci_parse($conn, "SELECT NVL(SUM(amount),0) AS total_income FROM PAYMENTS");
oci_execute($income_stmt);
$total_income = oci_fetch_assoc($income_stmt)['TOTAL_INCOME'];

$expense_stmt = oci_parse($conn, "SELECT NVL(SUM(order_cost),0) AS total_expense FROM EQUIPMENT_REQUEST");
oci_execute($expense_stmt);
$total_expense = oci_fetch_assoc($expense_stmt)['TOTAL_EXPENSE'];

$available_budget = $total_income - $total_expense;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Store Budget Overview</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 12px;
        }
        .ui-btn {
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
            font-family: "Times New Roman", Times, serif;
            border: none;
            border-radius: 10px;
            padding: 1.3em 0;
            font-weight: bold;
            width: 100%;
            text-align: center;
            transition: background-color 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            cursor: pointer;
        }
        .ui-btn:hover {
            background: #0056b3;
        }
        .budget-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0 0 0;
        }
        .budget-table th, .budget-table td {
            border: 1px solid #dee2e6;
            padding: 18px 30px;
            font-size: 19px;
            font-family: "Times New Roman", Times, serif;
        }
        .budget-table th {
            color: #212529;
            font-weight: 700;
            text-align: left;
            background: #e6f0fa;
        }
        .budget-table td {
            font-weight: 700;
            color: #0d6efd;
            text-align: right;
        }
        .budget-table td.final {
            color: <?= $available_budget < 0 ? "#c00" : "#198754" ?>;
            font-size: 21px;
        }
        .card h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Store Budget Overview</h2>

    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>

    <table class="budget-table">
        <tr>
            <th>Total Income</th>
            <td>₹<?= $total_income ?></td>
        </tr>
        <tr>
            <th>Total Expense</th>
            <td>₹<?= $total_expense ?></td>
        </tr>
        <tr>
            <th>Available Budget</th>
            <td class="final">₹<?= $available_budget ?></td>
        </tr>
    </table>

</div>
</body>
</html>
