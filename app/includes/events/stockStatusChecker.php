<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

// -----------------------------------------------------------
// 1️⃣ UPDATE INVENTORY STATUS (IGNORE ZERO-DUPLICATES FOR NOW)
// -----------------------------------------------------------
$conn->query("
    UPDATE inventory_item
    SET status = CASE
        WHEN quantity <= 0 THEN 'OUT OF STOCK'
        WHEN quantity <= 2000 THEN 'LOW STOCK'
        ELSE 'IN STOCK'
    END
");

// -----------------------------------------------------------
// 1B️⃣ MARK ZERO-QTY DUPLICATES AS UNAVAILABLE
// -----------------------------------------------------------
// Find item names that have:
//   - at least 1 qty > 0
//   - at least 1 qty = 0
$dupSql = "
    SELECT LOWER(item_name) AS item_name
    FROM inventory_item
    GROUP BY LOWER(item_name)
    HAVING SUM(quantity > 0) > 0
       AND SUM(quantity = 0) > 0
";
$stmt = $conn->query($dupSql);
$itemsToFix = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($itemsToFix)) {
    $inList = "'" . implode("','", array_map('strtolower', $itemsToFix)) . "'";
    $conn->query("
        UPDATE inventory_item
        SET status = 'UNAVAILABLE',
            expiry_status = 'UNAVAILABLE'
        WHERE quantity = 0
          AND LOWER(item_name) IN ($inList)
    ");
}

// -----------------------------------------------------------
// 2️⃣ CATEGORY 1 & 2 — TEA RULE BASE
// -----------------------------------------------------------
$conn->query("
    UPDATE category
    SET status = CASE 
            WHEN (SELECT SUM(quantity) 
                  FROM inventory_item 
                  WHERE LOWER(item_name) = 'tea') >= 250
            THEN 'ACTIVE'
            ELSE 'INACTIVE'
        END
    WHERE category_id IN (1,2)
");

// -----------------------------------------------------------
// 3️⃣ CATEGORY 3 & 6 — COFFEE RULE BASE
// -----------------------------------------------------------
$conn->query("
    UPDATE category
    SET status = CASE 
            WHEN (SELECT SUM(quantity) 
                  FROM inventory_item 
                  WHERE LOWER(item_name) = 'coffee') >= 250
            THEN 'ACTIVE'
            ELSE 'INACTIVE'
        END
    WHERE category_id IN (3,6)
");

// -----------------------------------------------------------
// 4️⃣ SYNC PRODUCT STATUS BASED ON CATEGORY + LINKED INVENTORY ≥ 40
// -----------------------------------------------------------
$conn->query("
    UPDATE product_details pd
    JOIN category c ON pd.category_id = c.category_id
    LEFT JOIN (
        SELECT product_id, SUM(quantity) AS total_quantity
        FROM inventory_item
        WHERE product_id IS NOT NULL
        GROUP BY product_id
    ) li ON li.product_id = pd.product_id
    SET pd.status = CASE 
        WHEN c.status = 'ACTIVE' AND IFNULL(li.total_quantity,0) >= 40 THEN 'active'
        ELSE 'inactive'
    END
");



// -----------------------------------------------------------
// 5️⃣ SYNC PRODUCT SIZES WITH PRODUCT STATUS
// -----------------------------------------------------------
// $conn->query("
//     UPDATE product_sizes ps
//     JOIN product_details pd ON ps.product_id = pd.product_id
//     SET ps.status = pd.status
// ");

// -----------------------------------------------------------
// 6️⃣ SYNC ADD-ONS BASED ON CATEGORY STATUS
// -----------------------------------------------------------
$conn->query("
    UPDATE product_add_ons pa
    LEFT JOIN inventory_item ii 
        ON LOWER(pa.add_ons_name) = LOWER(REPLACE(ii.item_name, ' AddOn', ''))
    SET pa.status = CASE
        WHEN ii.quantity >= 20 THEN 'active'
        ELSE 'inactive'
    END
");


// -----------------------------------------------------------
// 7️⃣ UPDATE EXPIRY STATUS
// -----------------------------------------------------------
$conn->query("
UPDATE inventory_item
SET expiry_status = CASE
    WHEN DATEDIFF(date_expiry, CURDATE()) <= 0 THEN 'EXPIRED'
    WHEN DATEDIFF(date_expiry, CURDATE()) <= 60 THEN 'SOON TO EXPIRE'
    ELSE 'FRESH'
END
WHERE status != 'UNAVAILABLE';

");


// -----------------------------------------------------------
echo json_encode(["status" => "success"]);
