<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

try {
    $conn->beginTransaction();

    // =========================
    // 1️⃣ ICED COFFEE INVENTORY DEDUCTION
    // =========================

    // Fetch all transaction items not yet deducted
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

    // Iced Coffee ingredient → inventory mapping
    $ingredientMap = [
        'cup' => ['medio' => 'cup_m', 'grande' => 'cup_g'],
        'straw' => 'Straw',
        'sealing film' => 'Sealing Film',
        'kape brusko syrup' => 'Kape Brusko Syrup',
        'kape karamel syrup' => 'Kape Karamel Syrup',
        'kape macch syrup' => 'Kape Macch Syrup',
        'kape vanilla syrup' => 'Kape Vanilla Syrup',
        'coffee' => 'coffee'
    ];

    foreach ($transaction_items as $item) {
        $productId = $item['PRODUCT_ID'];
        $quantityOrdered = $item['QUANTITY'];
        $size = strtolower($item['size_name']);

        // Fetch ingredient ratios
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

            if (isset($ingredientMap[$ingredientName])) {
                $ingredientName = is_array($ingredientMap[$ingredientName])
                    ? $ingredientMap[$ingredientName][$size]
                    : $ingredientMap[$ingredientName];
            }

            // Deduct inventory
            $updateInv = $conn->prepare("
                UPDATE inventory_item
                SET quantity = GREATEST(quantity - :deductQty, 0)
                WHERE LOWER(item_name) = LOWER(:ingredientName)
            ");
            $updateInv->execute([
                ':deductQty' => $deductQty,
                ':ingredientName' => $ingredientName
            ]);

            // Log deduction
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

    // Mark transactions as deducted
    $conn->prepare("
        UPDATE REG_TRANSACTION
        SET is_deducted = 1
        WHERE STATUS IN ('PENDING','PAID','NOW SERVING','COMPLETED','WASTE')
          AND is_deducted = 0
    ")->execute();

    // =========================
    // 2️⃣ STOCK CHECK & PRODUCT SIZE STATUS
    // =========================

    $categoryId = 1; // Milk Tea category

    // Get all products in this category
    $stmt = $conn->prepare("SELECT product_id FROM product_details WHERE category_id = :categoryId");
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
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

        // Default: set all sizes inactive
        $conn->prepare("UPDATE product_sizes SET status = 'inactive' WHERE product_id = :productId AND size IN ('medio','grande')")
            ->execute([':productId' => $productId]);

        // Logic based on stock
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
