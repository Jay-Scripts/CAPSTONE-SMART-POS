<?php
include "../../config/dbConnection.php";


$product_id = $_GET['product_id'];
$size = $_GET['size'];

$stmt = $conn->prepare("
    SELECT ingredient_name, ingredient_ratio
    FROM product_ingredient_ratio
    WHERE product_id = ? AND size = ?
");
$stmt->execute([$product_id, $size]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
