<?php
include "../../config/dbConnection.php";

$sql = "
SELECT 
    pa.add_ons_name,
    COUNT(ia.item_add_on_id) AS total_sold
FROM item_add_ons ia
JOIN transaction_item ti
    ON ia.item_id = ti.item_id
JOIN reg_transaction rt
    ON ti.reg_transaction_id = rt.reg_transaction_id
JOIN product_add_ons pa
    ON ia.add_ons_id = pa.add_ons_id
WHERE rt.status = 'COMPLETED'
GROUP BY pa.add_ons_name
ORDER BY total_sold DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$soldAddOns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($soldAddOns);
