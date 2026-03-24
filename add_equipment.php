<?php
include 'db_connect.php';

$categoryEquipments = [
    "Cricket" => ["Bat", "Ball", "Gloves", "Pads", "Helmet", "Stumps", "Cricket Shoes", "Arm Guard"],
    "Football" => ["Football", "Studs", "Shin Guards", "Jersey", "Goalkeeper Gloves", "Socks", "Training Cones"],
    "Badminton" => ["Racket", "Shuttlecock", "Net", "Grip", "Shoes", "String", "Bag"],
    "Swimming" => ["Goggles", "Swim Cap", "Costume", "Towel", "Fins", "Kickboard", "Pull Buoy"],
    "Gym" => ["Dumbbells", "Treadmill", "Bench Press", "Mat", "Kettlebell", "Resistance Band", "Medicine Ball"],
    "Tennis" => ["Racket", "Ball", "Net", "Shoes", "Grip", "String", "Bag"],
    "Basketball" => ["Ball", "Jersey", "Shoes", "Hoop", "Socks", "Wristbands", "Knee Pads"],
    "Table Tennis" => ["Racket", "Ball", "Net", "Table", "Shoes", "Paddle Case", "Table Cover"],
    "Hockey" => ["Stick", "Ball/Puck", "Pads", "Gloves", "Helmet", "Jersey", "Shoes", "Stick Grip"],
    "Yoga" => ["Mat", "Blocks", "Strap", "Bolster", "Blanket", "Wheel", "Ball", "Towel"]
];

$selected_category = isset($_POST['category_name']) ? $_POST['category_name'] : '';
$selected_equipment = isset($_POST['equipment_name']) ? $_POST['equipment_name'] : '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_equipment'])) {
    $category = $_POST['category_name'];
    $equipment = $_POST['equipment_name'];
    $price = (float)$_POST['price'];
    $qty = (int)$_POST['quantity'];

    if ($category && $equipment && $price > 0 && $qty >= 0) {
        // Check for existing equipment
        $checkStmt = oci_parse($conn, 
            "SELECT e.equipment_id, NVL(i.stock_level, 0) AS stock_level FROM Equipment e 
             LEFT JOIN Inventory i ON e.equipment_id = i.equipment_id 
             WHERE e.equipment_name = :name AND e.category_name = :category AND e.price = :price");
        oci_bind_by_name($checkStmt, ":name", $equipment);
        oci_bind_by_name($checkStmt, ":category", $category);
        oci_bind_by_name($checkStmt, ":price", $price);
        oci_execute($checkStmt);
        $existing = oci_fetch_assoc($checkStmt);

        if ($existing) {
            // Update the stock_level in Inventory
            $newQty = $existing['STOCK_LEVEL'] + $qty;

            $updateInv = oci_parse($conn, "UPDATE Inventory SET stock_level = :newqty WHERE equipment_id = :eid");
            oci_bind_by_name($updateInv, ":newqty", $newQty);
            oci_bind_by_name($updateInv, ":eid", $existing['EQUIPMENT_ID']);
            $resInv = oci_execute($updateInv);

            if ($resInv) {
                oci_commit($conn);
                $message = "Existing equipment stock updated successfully.";
            } else {
                oci_rollback($conn);
                $message = "Failed to update stock quantity.";
            }
        } else {
            // Insert new Equipment
            $insertEq = oci_parse($conn, "INSERT INTO Equipment (equipment_id, category_name, equipment_name, price) VALUES (equipment_seq.NEXTVAL, :cat, :en, :pr)");
            oci_bind_by_name($insertEq, ":cat", $category);
            oci_bind_by_name($insertEq, ":en", $equipment);
            oci_bind_by_name($insertEq, ":pr", $price);

            $resEq = oci_execute($insertEq, OCI_NO_AUTO_COMMIT);

            if ($resEq) {
                $stmtCurr = oci_parse($conn, "SELECT equipment_seq.CURRVAL AS last_id FROM dual");
                oci_execute($stmtCurr);
                $row = oci_fetch_assoc($stmtCurr);
                $eqId = $row['LAST_ID'];

                $insertInv = oci_parse($conn, "INSERT INTO Inventory (equipment_id, stock_level) VALUES (:eq_id, :qty)");
                oci_bind_by_name($insertInv, ':eq_id', $eqId);
                oci_bind_by_name($insertInv, ':qty', $qty);

                $resInv = oci_execute($insertInv, OCI_NO_AUTO_COMMIT);

                if ($resInv) {
                    oci_commit($conn);
                    $message = "Equipment and stock level added successfully.";
                } else {
                    oci_rollback($conn);
                    $message = "Failed to add stock quantity.";
                }
            } else {
                oci_rollback($conn);
                $message = "Failed to add equipment.";
            }
        }
    } else {
        $message = "Please fill all fields correctly.";
    }
}
?>
<!-- Your HTML/CSS from previous version remains untouched below this line -->


<!DOCTYPE html>
<html>
<head>
    <title>Add Equipment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .ui-button-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .ui-btn {
            background-color: #007bff;
            color: white;
            font-size: 1.15rem;
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
            display: block;
        }
        .ui-btn:hover {
            background: #0056b3;
        }
        form label {
            font-weight: 600; 
            color: #2866a4; 
            margin-bottom: 6px; 
            display: block;
            font-family: "Times New Roman", Times, serif;
        }
        form select, form input {
            width: 100%; 
            margin-bottom: 15px;
            padding: 8px 10px; 
            border-radius: 5px; 
            border: 1px solid #b2b2b2; 
            font-size: 15px;
            font-family: "Times New Roman", Times, serif;
        }
        .card h2 {
            font-family: "Times New Roman", Times, serif;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
        }
        table.table {
            margin-top: 20px;
            width: 100%;
            font-family: "Times New Roman", Times, serif;
            border-collapse: collapse;
        }
        table.table th, table.table td {
            border: 1px solid #ddd;
            padding: 14px 20px;
            text-align: left;
        }
        table.table th {
            background-color: #007bff;
            color: white;
            font-weight: 600;
        }
        table.table td {
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="ui-button-grid">
        <a href="index.php" class="ui-btn">Back to Dashboard</a>
    </div>
    <h2>Add Equipment</h2>
    <form method="POST">
        <label>Category Name:</label>
        <select name="category_name" onchange="this.form.submit()" required>
            <option value="">Select Category</option>
            <?php
            foreach ($categoryEquipments as $cat => $equipments) {
                $selected = ($cat == $selected_category) ? "selected" : "";
                echo "<option value='$cat' $selected>$cat</option>";
            }
            ?>
        </select>

        <label>Equipment Name:</label>
        <select name="equipment_name" required>
            <option value="">Select Equipment</option>
            <?php
            if ($selected_category != '') {
                foreach ($categoryEquipments[$selected_category] as $equip) {
                    $sel = ($equip == $selected_equipment) ? "selected" : "";
                    echo "<option value='$equip' $sel>$equip</option>";
                }
            }
            ?>
        </select>

        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label>Quantity:</label>
        <input type="number" name="quantity" min="0" required>

        <button type="submit" name="submit_equipment" class="ui-btn">Add Equipment</button>
        <a href="view_equipment.php" class="ui-btn" style="background:#eee; color:#2866a4; font-weight: normal;">Cancel</a>
    </form>

    <?php if ($message != "") echo "<p style='margin-top:20px; font-family: Times New Roman, serif;'>$message</p>"; ?>

    <hr>
    <h3 style="font-family: Times New Roman, serif;">Existing Equipment</h3>
    <table class="table" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th><th>Category</th><th>Equipment</th><th>Price</th><th>Stock Level</th>
        </tr>
        <?php
        $res = oci_parse($conn, "SELECT e.equipment_id, e.category_name, e.equipment_name, e.price, NVL(i.stock_level, 0) AS stock_level
                                    FROM Equipment e LEFT JOIN Inventory i ON e.equipment_id = i.equipment_id ORDER BY e.equipment_id DESC");
        oci_execute($res);
        while ($row = oci_fetch_assoc($res)) {
            echo "<tr>
                    <td>{$row['EQUIPMENT_ID']}</td>
                    <td>{$row['CATEGORY_NAME']}</td>
                    <td>{$row['EQUIPMENT_NAME']}</td>
                    <td>{$row['PRICE']}</td>
                    <td>{$row['STOCK_LEVEL']}</td>
                  </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
