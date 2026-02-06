<?php
include "../../config/dbConnection.php";

$product_id = $_GET['product_id'];
$size_id = $_GET['size_id'];
$qty = $_GET['qty'];

// 1️⃣ Get size name
$stmt = $conn->prepare("SELECT size FROM product_sizes WHERE size_id=?");
$stmt->execute([$size_id]);
$size = $stmt->fetchColumn();

// 2️⃣ Map size to ml per cup
$size_ml_map = [
    'MEDIO' => 250,
    'GRANDE' => 350
];

// 3️⃣ Get ingredient ratios for this product & size
$stmt = $conn->prepare("
    SELECT ingredient_name, ingredient_ratio
    FROM product_ingredient_ratio
    WHERE product_id=? AND size=?
");
$stmt->execute([$product_id, $size]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4️⃣ Check only tea or coffee
foreach ($ingredients as $ing) {
    $ingredient = strtolower($ing['ingredient_name']);
    if (!in_array($ingredient, ['tea', 'coffee'])) {
        continue; // skip anything that's not tea or coffee
    }

    // 5️⃣ Get stock for this ingredient
    $stmt2 = $conn->prepare("
        SELECT quantity
        FROM inventory_item
        WHERE LOWER(item_name)=LOWER(?)
        AND status!='OUT OF STOCK'
        LIMIT 1
    ");
    $stmt2->execute([$ing['ingredient_name']]);
    $stock_ml = $stmt2->fetchColumn() ?: 0;

    // 6️⃣ Calculate how many cups are left for each size
    $pcs_left = [];
    foreach ($size_ml_map as $s => $ml_per_cup) {
        $pcs_left[$s] = floor($stock_ml / $ml_per_cup);
    }

    // 7️⃣ Check current order quantity for this size
    $needed_cups = $qty; // assume each order = 1 cup unit
    if ($needed_cups > $pcs_left[strtoupper($size)]) {
        $msg_parts = [];
        foreach ($pcs_left as $s => $pcs) {
            $msg_parts[] = "$pcs PCS $s";
        }
        $msg = implode(' / ', $msg_parts);

        echo json_encode([
            "ok" => false,
            "message" => "Not enough {$ing['ingredient_name']} (Can serve only $msg)"
        ]);
        exit;
    }
}

// ✅ Stock is enough
echo json_encode(["ok" => true]);
