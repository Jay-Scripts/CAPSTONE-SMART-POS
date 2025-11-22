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
// 2️⃣ CATEGORY 1 & 2 — TEA RULE
// -----------------------------------------------------------
$conn->query("
    UPDATE category
    SET status = CASE 
            WHEN (SELECT SUM(quantity) 
                  FROM inventory_item 
                  WHERE LOWER(item_name) = 'tea') > 250
            THEN 'ACTIVE'
            ELSE 'INACTIVE'
        END
    WHERE category_id IN (1,2)
");

// -----------------------------------------------------------
// 3️⃣ CATEGORY 3 & 6 — COFFEE RULE
// -----------------------------------------------------------
$conn->query("
    UPDATE category
    SET status = CASE 
            WHEN (SELECT SUM(quantity) 
                  FROM inventory_item 
                  WHERE LOWER(item_name) = 'coffee') > 250
            THEN 'ACTIVE'
            ELSE 'INACTIVE'
        END
    WHERE category_id IN (3,6)
");

// -----------------------------------------------------------
// 4️⃣ SYNC PRODUCT STATUS BASED ON CATEGORY
// -----------------------------------------------------------
$conn->query("
    UPDATE product_details pd
    JOIN category c ON pd.category_id = c.category_id
    SET pd.status = CASE 
        WHEN c.status = 'ACTIVE' THEN 'active'
        ELSE 'inactive'
    END
");

// -----------------------------------------------------------
// 5️⃣ SYNC PRODUCT SIZES WITH PRODUCT STATUS
// -----------------------------------------------------------
$conn->query("
    UPDATE product_sizes ps
    JOIN product_details pd ON ps.product_id = pd.product_id
    SET ps.status = pd.status
");

// -----------------------------------------------------------
// 6️⃣ SYNC ADD-ONS BASED ON CATEGORY STATUS
// -----------------------------------------------------------
// Cheese Cake AddOn
$conn->query("
    UPDATE product_add_ons
    SET status = CASE 
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Cheese Cake AddOn') >= 20
        THEN 'active'
        ELSE 'inactive'
    END
    WHERE add_ons_name = 'CHEESE CAKE'
");

// Pearl AddOn
$conn->query("
    UPDATE product_add_ons
    SET status = CASE 
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Pearl AddOn') >= 20
        THEN 'active'
        ELSE 'inactive'
    END
    WHERE add_ons_name = 'PEARL'
");

// Cream Cheese AddOn
$conn->query("
    UPDATE product_add_ons
    SET status = CASE 
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Cream Cheese AddOn') >= 20
        THEN 'active'
        ELSE 'inactive'
    END
    WHERE add_ons_name = 'CREAM CHEESE'
");

// Coffee Jelly AddOn
$conn->query("
    UPDATE product_add_ons
    SET status = CASE 
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Coffee Jelly AddOn') >= 20
        THEN 'active'
        ELSE 'inactive'
    END
    WHERE add_ons_name = 'COFFEE JELLY'
");

// Crushed Oreo AddOn
$conn->query("
    UPDATE product_add_ons
    SET status = CASE 
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Crushed Oreo AddOn') >= 20
        THEN 'active'
        ELSE 'inactive'
    END
    WHERE add_ons_name = 'CRUSHED OREO'
");

// Chia Seed AddOn
$conn->query("
    UPDATE product_add_ons
    SET status = CASE 
        WHEN (SELECT quantity FROM inventory_item WHERE item_name = 'Chia Seed AddOn') >= 20
        THEN 'active'
        ELSE 'inactive'
    END
    WHERE add_ons_name = 'CHIA SEED'
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
