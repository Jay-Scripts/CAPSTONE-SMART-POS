<?php
include "../../config/dbConnection.php";

$today = date('Y-m-d');

$sql = "
SELECT 
    c.category_name,
    SUM(ti.quantity) AS total_sold
FROM transaction_item ti
JOIN reg_transaction rt 
    ON ti.reg_transaction_id = rt.reg_transaction_id
JOIN product_details pd
    ON ti.product_id = pd.product_id
JOIN category c
    ON pd.category_id = c.category_id
WHERE rt.status = 'COMPLETED'
  AND DATE(rt.date_added) = :today
GROUP BY c.category_name
ORDER BY total_sold DESC
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':today', $today);
$stmt->execute();
$soldByCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($soldByCategory);
