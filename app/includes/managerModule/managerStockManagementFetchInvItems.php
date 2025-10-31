<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

try {
    // Materials
    $materials = $conn->prepare("
        SELECT 
            ii.item_id, ii.item_name, ii.quantity, ii.unit, ii.status, 
            ii.date_made, ii.date_expiry, si.staff_name AS added_by
        FROM inventory_item ii
        LEFT JOIN staff_info si ON ii.added_by = si.staff_id
        WHERE ii.inv_category_id = 2
        ORDER BY ii.item_name
    ");
    $materials->execute();
    $materialsItems = $materials->fetchAll(PDO::FETCH_ASSOC);

    // Product categories
    $data = $conn->query("
        SELECT 
            c.category_id, c.category_name,
            ii.item_id, ii.item_name, ii.quantity, ii.unit, ii.status,
            ii.date_made, ii.date_expiry, si.staff_name AS added_by
        FROM category c
        LEFT JOIN inventory_item ii ON c.category_id = ii.category_id
        LEFT JOIN staff_info si ON ii.added_by = si.staff_id
        ORDER BY c.category_id, ii.item_name
    ")->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['category_name']]['items'][] = $row;
    }

    echo json_encode([
        'materials' => $materialsItems,
        'categories' => $grouped
    ]);
} catch (PDOException $e) {
    echo json_encode(['materials' => [], 'categories' => []]);
}
