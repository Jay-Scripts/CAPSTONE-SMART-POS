<?php
include "../../config/dbConnection.php";


header('Content-Type: application/json');

try {
    $materials = $conn->prepare("
        SELECT ii.item_id, ii.item_name, ii.quantity, ii.unit, ii.status, ii.date_made, ii.date_expiry, si.staff_name AS added_by
        FROM inventory_item ii
        LEFT JOIN staff_info si ON ii.added_by = si.staff_id
        WHERE ii.inv_category_id = 2
        ORDER BY ii.item_name
    ");
    $materials->execute();
    $materialsItems = $materials->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['materials' => $materialsItems]);
} catch (PDOException $e) {
    echo json_encode(['materials' => []]);
}
