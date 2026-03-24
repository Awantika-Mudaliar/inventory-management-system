<?php
$order_id = $_GET['order_id'] ?? '';
?>
<form method="post" action="finalize_order.php">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>" />
    
    <label for="payment_mode">Select Payment Mode:</label>
    <select name="payment_mode" id="payment_mode" required>
        <option value="">--Select Payment Mode--</option>
        <option value="UPI">UPI</option>
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="Net Banking">Net Banking</option>
    </select>
    
    <button type="submit">Finalize Order</button>
</form>
