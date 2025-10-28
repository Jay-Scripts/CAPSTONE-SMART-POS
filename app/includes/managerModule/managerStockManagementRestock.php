<?php
include "../../config/dbConnection.php";
header("Content-Type: application/json");

try {
    $query = "
        SELECT 
            ic.inv_category_id,
            ic.category_name,
            ii.item_id,
            ii.item_name,
            ii.quantity,
            ii.unit,
            ii.status,
            ii.date_made,
            ii.date_expiry
        FROM inventory_category ic
        LEFT JOIN inventory_item ii ON ic.inv_category_id = ii.inv_category_id
        ORDER BY ic.category_name, ii.item_name
    ";

    $stmt = $conn->query($query);
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $catId = $row['inv_category_id'];
        if (!isset($data[$catId])) {
            $data[$catId] = [
                'category_name' => $row['category_name'],
                'items' => []
            ];
        }

        if ($row['item_id']) {
            $data[$catId]['items'][] = [
                'item_id' => $row['item_id'],
                'item_name' => $row['item_name'],
                'quantity' => $row['quantity'],
                'unit' => $row['unit'],
                'status' => $row['status'],
                'date_made' => $row['date_made'],
                'date_expiry' => $row['date_expiry']
            ];
        }
    }

    echo json_encode(['success' => true, 'data' => array_values($data)]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
