<?php
include "../../config/dbConnection.php";


// Today's date
$today = date('Y-m-d');

$sql = "
SELECT pd.product_name, SUM(ti.QUANTITY) AS total_sold
FROM TRANSACTION_ITEM ti
JOIN REG_TRANSACTION rt ON ti.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
JOIN PRODUCT_DETAILS pd ON ti.PRODUCT_ID = pd.PRODUCT_ID
WHERE rt.STATUS = 'COMPLETED'
  AND DATE(rt.date_added) = :today
GROUP BY ti.PRODUCT_ID
ORDER BY total_sold DESC
LIMIT 5
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':today', $today);
$stmt->execute();
$topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($topProducts);
