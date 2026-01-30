<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

try {
    $conn->beginTransaction();

    // 1️⃣ Fetch all transaction items not yet deducted
    $stmt = $conn->prepare("
        SELECT ti.ITEM_ID, ti.PRODUCT_ID, ti.SIZE_ID, ti.QUANTITY, ps.size AS size_name
        FROM TRANSACTION_ITEM ti
        JOIN product_sizes ps ON ps.SIZE_ID = ti.SIZE_ID
        JOIN REG_TRANSACTION r ON r.REG_TRANSACTION_ID = ti.REG_TRANSACTION_ID
        WHERE r.is_deducted = 0
          AND r.STATUS IN ('PENDING','PAID','NOW SERVING','COMPLETED','WASTE')
    ");
    $stmt->execute();
    $transaction_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($transaction_items as $item) {
        $productId = $item['PRODUCT_ID'];
        $quantityOrdered = $item['QUANTITY'];
        $size = $item['size_name']; // 'medio', 'grande', 'promo', 'hot brew'

        // 2️⃣ Fetch ingredient ratios for this product & size
        $stmt2 = $conn->prepare("
            SELECT ingredient_name, ingredient_ratio
            FROM product_ingredient_ratio
            WHERE product_id = :productId
              AND size = :size
        ");
        $stmt2->execute([
            ':productId' => $productId,
            ':size' => $size
        ]);
        $ingredients = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ingredients as $ing) {
            $ingredientName = $ing['ingredient_name'];
            $deductQty = $ing['ingredient_ratio'] * $quantityOrdered;

            // 🔹 Adjust cup names based on size
            if (strtolower($ingredientName) === 'cup') {
                if ($size === 'medio') {
                    $ingredientName = 'cup_m';
                } elseif ($size === 'grande') {
                    $ingredientName = 'cup_g';
                }
            }

            // 3️⃣ Deduct from inventory_item by name
            $updateInv = $conn->prepare("
        UPDATE inventory_item
        SET quantity = GREATEST(quantity - :deductQty, 0)
        WHERE LOWER(item_name) = LOWER(:ingredientName)
    ");
            $updateInv->execute([
                ':deductQty' => $deductQty,
                ':ingredientName' => $ingredientName
            ]);

            // Optional: log deduction
            $conn->prepare("
        INSERT INTO inventory_item_logs(item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after, remarks)
        SELECT ii.item_id, 1, 'AUTO DEDUCTION', ii.quantity + :deductQty, :deductQty, ii.quantity, 'Auto Deduction'
        FROM inventory_item ii
        WHERE LOWER(ii.item_name) = LOWER(:ingredientName)
    ")->execute([
                ':deductQty' => $deductQty,
                ':ingredientName' => $ingredientName
            ]);
        }
    }

    // 4️⃣ Mark transactions as deducted
    $conn->prepare("
        UPDATE REG_TRANSACTION
        SET is_deducted = 1
        WHERE STATUS IN ('PENDING','PAID','NOW SERVING','COMPLETED','WASTE')
          AND is_deducted = 0
    ")->execute();

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Inventory deducted successfully"]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
