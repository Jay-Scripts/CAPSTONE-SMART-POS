<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

try {
    $conn->beginTransaction();

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
        'cup' => ['medio' => 'cup_m', 'grande' => 'cup_g'],
        'straw' => 'Straw',
        'sealing film' => 'Sealing Film',
        'coffee' => 'coffee',
        // Milk Tea / Fruit Tea Syrups
        'kape brusko syrup' => 'Kape Brusko Syrup',
        'kape karamel syrup' => 'Kape Karamel Syrup',
        'kape macch syrup' => 'Kape Macch Syrup',
        'kape vanilla syrup' => 'Kape Vanilla Syrup',
        'hot brusko syrup' => 'Hot Brusko Syrup',
        'hot choco syrup' => 'Hot Choco Syrup',
        'hot moca syrup' => 'Hot Moca Syrup',
        'hot matcha syrup' => 'Hot Matcha Syrup',
        'hot karamel syrup' => 'Hot Karamel Syrup'
    ];

    // =========================
    // 3️⃣ DEDUCT INVENTORY
    // =========================
    foreach ($transaction_items as $item) {
        $productId = $item['PRODUCT_ID'];
        $quantityOrdered = $item['QUANTITY'];
        $size = strtolower($item['size_name']); // medio / grande

        // Fetch ingredient ratios for this product & size
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
            $ingredientName = strtolower($ing['ingredient_name']);
            $deductQty = $ing['ingredient_ratio'] * $quantityOrdered;

            // Map ingredient to inventory item
            if (isset($ingredientMap[$ingredientName])) {
                $ingredientName = is_array($ingredientMap[$ingredientName])
                    ? $ingredientMap[$ingredientName][$size]
                    : $ingredientMap[$ingredientName];
            }
            // =========================
            // FEFO INVENTORY DEDUCTION
            // =========================
            $remaining = $deductQty;

            // Get inventory batches ordered by nearest expiry
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

                // Update inventory batch
                $conn->prepare("
        UPDATE inventory_item
        SET quantity = quantity - :deduct
        WHERE item_id = :item_id
    ")->execute([
                    ':deduct' => $deductNow,
                    ':item_id' => $inv['item_id']
                ]);

                // Log deduction per batch
                $conn->prepare("
        INSERT INTO inventory_item_logs
        (item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after, remarks)
        VALUES
        (:item_id, 1, 'AUTO DEDUCTION',
         :last_qty, :deduct, :after_qty,
         'FEFO Auto Deduction')
    ")->execute([
                    ':item_id'   => $inv['item_id'],
                    ':last_qty'  => $inv['quantity'],
                    ':deduct'    => $deductNow,
                    ':after_qty' => $inv['quantity'] - $deductNow
                ]);

                $remaining -= $deductNow;
            }
        }
    }

    // Mark all transactions as deducted
    $conn->prepare("
        UPDATE REG_TRANSACTION
        SET is_deducted = 1
        WHERE STATUS IN ('PENDING','PAID','NOW SERVING','COMPLETED','WASTE')
          AND is_deducted = 0
    ")->execute();

    // =========================
    // 4️⃣ UPDATE PRODUCT SIZE STATUS BASED ON INVENTORY
    // =========================
    $stmt = $conn->prepare("SELECT product_id, category_id FROM product_details");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $productId = $product['product_id'];

        // Sum all inventory linked to this product
        $stmt2 = $conn->prepare("SELECT SUM(quantity) AS total_stock FROM inventory_item WHERE product_id = :productId");
        $stmt2->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt2->execute();
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);
        $total_stock = floatval($row['total_stock'] ?? 0);

        // Set sizes inactive by default
        $conn->prepare("UPDATE product_sizes SET status = 'inactive' WHERE product_id = :productId AND size IN ('medio','grande')")
            ->execute([':productId' => $productId]);

        // Activate sizes based on stock
        if ($total_stock >= 60) {
            $conn->prepare("UPDATE product_sizes SET status = 'active' WHERE product_id = :productId AND size IN ('medio','grande')")
                ->execute([':productId' => $productId]);
            $status = 'active';
        } elseif ($total_stock > 40) {
            $conn->prepare("UPDATE product_sizes SET status = 'active' WHERE product_id = :productId AND size = 'medio'")
                ->execute([':productId' => $productId]);
            $status = 'active';
        } else {
            $status = 'inactive';
        }

        // Update product details status
        $conn->prepare("UPDATE product_details SET status = :status WHERE product_id = :productId")
            ->execute([':status' => $status, ':productId' => $productId]);
    }

    $conn->commit();

    echo json_encode(["status" => "success", "message" => "Deduction and stock check completed"]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
