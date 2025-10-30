<?php
include "../../config/dbConnection.php";

try {
    $query = "
    SELECT 
      c.category_id, 
      c.category_name, 
      ii.item_id, 
      ii.item_name, 
      ii.quantity, 
      ii.unit, 
      ii.status, 
      ii.date_made, 
      ii.date_expiry, 
      si.staff_name AS added_by
    FROM category c
    LEFT JOIN inventory_item ii ON c.category_id = ii.category_id
    LEFT JOIN staff_info si ON ii.added_by = si.staff_id
    ORDER BY c.category_id, ii.item_name
  ";
    $stmt = $conn->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['category_id']]['category_name'] = $row['category_name'];
        $grouped[$row['category_id']]['items'][] = $row;
    }
} catch (PDOException $e) {
    echo "<p class='text-red-500'>Error: " . $e->getMessage() . "</p>";
}
