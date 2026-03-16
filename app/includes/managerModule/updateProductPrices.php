<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);

if (empty($body['sizes']) || !is_array($body['sizes'])) {
    echo json_encode(['success' => false, 'message' => 'No size data provided.']);
    exit;
}

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("
        UPDATE product_sizes
        SET regular_price = :regular_price,
            promo_price   = :promo_price
        WHERE size_id = :size_id
    ");

    foreach ($body['sizes'] as $size) {
        // Basic validation
        $size_id       = intval($size['size_id']       ?? 0);
        $regular_price = floatval($size['regular_price'] ?? 0);
        $promo_price   = floatval($size['promo_price']   ?? 0);

        if ($size_id <= 0) continue;
        if ($regular_price < 0 || $promo_price < 0) {
            throw new Exception("Prices cannot be negative (size_id: {$size_id}).");
        }

        $stmt->execute([
            ':size_id'       => $size_id,
            ':regular_price' => $regular_price,
            ':promo_price'   => $promo_price,
        ]);
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Prices updated successfully.']);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
