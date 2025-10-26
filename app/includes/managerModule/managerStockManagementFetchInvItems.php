<?php
header("Content-Type: application/json");
include "../../config/dbConnection.php";

try {
    $loadInventoryQuery = "
        SELECT 
            ii.item_id,
            ii.item_name,
            ii.quantity,
            ii.unit,
            ii.status,
            ii.date_made,
            ii.date_expiry,
            ic.category_name,
            si.staff_name AS added_by_name
        FROM inventory_item ii
        JOIN inventory_category ic ON ii.inv_category_id = ic.inv_category_id
        JOIN staff_info si ON ii.added_by = si.staff_id
        ORDER BY ii.date_added DESC
    ";

    $stmt = $conn->query($loadInventoryQuery);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($items);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
