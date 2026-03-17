<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

try {
    $conn->beginTransaction();

    // =========================
    // GET SYSTEM STAFF ID
    // =========================
    $action = 'SYSTEM';
    $sysStmt = $conn->prepare("SELECT staff_id FROM staff_info WHERE staff_name = 'SYSTEM' LIMIT 1");
    $sysStmt->execute();
    $systemStaffId = $sysStmt->fetchColumn() ?: 1;

    // =========================
    // 1️⃣ FETCH ALL TRANSACTION ITEMS NOT YET DEDUCTED
    // =========================
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

    // =========================
    // 2️⃣ INGREDIENT TO INVENTORY MAPPING
    // =========================
    $ingredientMap = [
        'cup'               => ['medio' => 'cup_m', 'grande' => 'cup_g'],
        'straw'             => 'Straw',
        'sealing film'      => 'Sealing Film',
        'coffee'            => 'coffee',
        'kape brusko syrup' => 'Kape Brusko Syrup',
        'kape karamel syrup' => 'Kape Karamel Syrup',
        'kape macch syrup'  => 'Kape Macch Syrup',
        'kape vanilla syrup' => 'Kape Vanilla Syrup',
        'hot brusko syrup'  => 'Hot Brusko Syrup',
        'hot choco syrup'   => 'Hot Choco Syrup',
        'hot moca syrup'    => 'Hot Moca Syrup',
        'hot matcha syrup'  => 'Hot Matcha Syrup',
        'hot karamel syrup' => 'Hot Karamel Syrup',
    ];

    // =========================
    // 3️⃣ DEDUCT INVENTORY (FEFO)
    // =========================
    foreach ($transaction_items as $item) {
        $productId       = $item['PRODUCT_ID'];
        $quantityOrdered = $item['QUANTITY'];
        $size            = strtolower($item['size_name']);

        $stmt2 = $conn->prepare("
            SELECT ingredient_name, ingredient_ratio
            FROM product_ingredient_ratio
            WHERE product_id = :productId
              AND size = :size
        ");
        $stmt2->execute([':productId' => $productId, ':size' => $size]);
        $ingredients = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ingredients as $ing) {
            $ingredientName = strtolower($ing['ingredient_name']);
            $deductQty      = $ing['ingredient_ratio'] * $quantityOrdered;

            if (isset($ingredientMap[$ingredientName])) {
                $ingredientName = is_array($ingredientMap[$ingredientName])
                    ? $ingredientMap[$ingredientName][$size]
                    : $ingredientMap[$ingredientName];
            }

            $remaining = $deductQty;

            $stmtInv = $conn->prepare("
                SELECT item_id, quantity
                FROM inventory_item
                WHERE LOWER(item_name) = LOWER(:ingredientName)
                  AND quantity > 0
                  AND expiry_status != 'EXPIRED'
                ORDER BY date_expiry ASC, date_added ASC
                FOR UPDATE
            ");
            $stmtInv->execute([':ingredientName' => $ingredientName]);
            $inventoryRows = $stmtInv->fetchAll(PDO::FETCH_ASSOC);

            foreach ($inventoryRows as $inv) {
                if ($remaining <= 0) break;

                $deductNow = min($inv['quantity'], $remaining);

                $conn->prepare("
                    UPDATE inventory_item
                    SET quantity = quantity - :deduct
                    WHERE item_id = :item_id
                ")->execute([':deduct' => $deductNow, ':item_id' => $inv['item_id']]);

                $conn->prepare("
    INSERT INTO inventory_item_logs
    (item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after, remarks)
    VALUES (:item_id, :staff_id, :action_type, :last_qty, :deduct, :after_qty, 'FEFO Auto Deduction')
")->execute([
                    ':item_id'     => $inv['item_id'],
                    ':staff_id'    => $systemStaffId,
                    ':action_type' => $action,        // ← now matches the placeholder
                    ':last_qty'    => $inv['quantity'],
                    ':deduct'      => $deductNow,
                    ':after_qty'   => $inv['quantity'] - $deductNow,
                ]);

                $remaining -= $deductNow;
            }
        }
    }

    // =========================
    // MARK TRANSACTIONS AS DEDUCTED
    // =========================
    $conn->prepare("
        UPDATE REG_TRANSACTION
        SET is_deducted = 1
        WHERE STATUS IN ('PENDING','PAID','NOW SERVING','COMPLETED','WASTE')
          AND is_deducted = 0
    ")->execute();

    $conn->commit();

    echo json_encode(["status" => "success", "message" => "Inventory deduction completed"]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
