<?php
include "../../config/dbConnection.php";

$category_id = $_GET['category_id'] ?? 1; // Default to Milk Tea

$sql = "
SELECT 
    pd.product_name,
    SUM(ti.quantity) AS total_sold
FROM transaction_item ti
JOIN reg_transaction rt
    ON ti.reg_transaction_id = rt.reg_transaction_id
JOIN product_details pd
    ON ti.product_id = pd.product_id
WHERE rt.status = 'COMPLETED'
  AND pd.category_id = :category_id
GROUP BY pd.product_name
ORDER BY total_sold DESC
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
