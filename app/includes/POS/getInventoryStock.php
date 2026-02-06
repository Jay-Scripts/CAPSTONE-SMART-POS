<?php
include "../../config/dbConnection.php";


$stmt = $conn->query("
    SELECT item_name, quantity
    FROM inventory_item
    WHERE status != 'OUT OF STOCK'
");

echo json_encode($stmt->fetchAll(PDO::FETCH_KEY_PAIR));
