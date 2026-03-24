<?php
session_start();
include 'db_connect.php';

$message = '';

$cart_items = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $equipment_id => $qty) {
        $stmt = oci_parse($conn, "SELECT EQUIPMENT_NAME, PRICE FROM EQUIPMENT WHERE EQUIPMENT_ID = :eid");
        oci_bind_by_name($stmt, ':eid', $equipment_id);
        oci_execute($stmt);
        $data = oci_fetch_assoc($stmt);
        if ($data) {
            $cart_items[] = [
                'equipment_id' => $equipment_id,
                'equipment_name' => $data['EQUIPMENT_NAME'],
                'price' => $data['PRICE'],
                'quantity' => $qty,
                'total_price' => $data['PRICE'] * $qty,
            ];
        }
    }
}

$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['total_price'];
}

if (isset($_POST['finalize'])) {
    $total_income_stmt = oci_parse($conn, "SELECT NVL(SUM(amount),0) AS total_income FROM PAYMENTS");
    oci_execute($total_income_stmt);
    $income_row = oci_fetch_assoc($total_income_stmt);
    $total_income = $income_row ? $income_row['TOTAL_INCOME'] : 0;

    $total_expense_stmt = oci_parse($conn, "SELECT NVL(SUM(order_cost),0) AS total_expense FROM EQUIPMENT_REQUEST");
    oci_execute($total_expense_stmt);
    $expense_row = oci_fetch_assoc($total_expense_stmt);
    $total_expense = $expense_row ? $expense_row['TOTAL_EXPENSE'] : 0;

    $available_budget = $total_income - $total_expense;

    if ($total_amount > $available_budget) {
        $message = "Insufficient budget! Cart total ₹$total_amount but available budget is ₹$available_budget";
    } else {
        $insert_stmt = oci_parse($conn, "INSERT INTO EQUIPMENT_REQUEST (REQUEST_ID, EQUIPMENT, QUANTITY, ORDER_COST) VALUES (equipment_request_seq.NEXTVAL, :equipment, :quantity, :order_cost)");
        foreach ($cart_items as $item) {
            oci_bind_by_name($insert_stmt, ':equipment', $item['equipment_name']);
            oci_bind_by_name($insert_stmt, ':quantity', $item['quantity']);
            oci_bind_by_name($insert_stmt, ':order_cost', $item['total_price']);
            oci_execute($insert_stmt);
        }
        unset($_SESSION['cart']);
        $message = "Order finalized successfully!";
        header("Refresh:2");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Cart & Checkout</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .cart-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px 20px;
            background-color: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-name {
            font-weight: 700;
            font-size: 18px;
            color: #222;
            font-family: "Times New Roman", Times, serif;
        }
        .item-details {
            display: flex;
            gap: 30px;
            font-size: 16px;
            color: #555;
            font-family: "Times New Roman", Times, serif;
        }
        .cart-total {
            margin-top: 10px;
            text-align: right;
            font-size: 20px;
            color: #007bff;
            font-weight: 700;
            font-family: "Times New Roman", Times, serif;
        }
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 19px;
            margin-top: 24px;
        }
        .ui-btn {
            background-color: #007bff;
            color: white;
            font-size: 1.12rem;
            font-family: "Times New Roman", Times, serif;
            border: none;
            border-radius: 10px;
            padding: 1.2em 0;
            font-weight: bold;
            width: 100%;
            text-align: center;
            transition: background-color 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            cursor: pointer;
            display: block;
        }
        .ui-btn:hover {
            background: #0056b3;
        }
        @media (max-width: 800px) {
            .card { width: 97vw !important; padding: 14px 4vw; max-width: 98vw;}
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Your Cart</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>

    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <div class="cart-items">
            <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <div class="item-name"><?= htmlspecialchars($item['equipment_name']) ?></div>
                <div class="item-details">
                    <div>Quantity: <?= $item['quantity'] ?></div>
                    <div>Unit Price: ₹<?= $item['price'] ?></div>
                    <div>Total: ₹<?= $item['total_price'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="cart-total">
                <strong>Total Amount: ₹<?= $total_amount ?></strong>
            </div>
        </div>

        <form method="post" style="margin-top:18px;">
            <button type="submit" name="finalize" class="ui-btn">Finalize Order</button>
        </form>
    <?php endif; ?>

    <div class="ui-button-grid">
      <a href="add_to_cart.php" class="ui-btn">Add More Equipment</a>
      <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
