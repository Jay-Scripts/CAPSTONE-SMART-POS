<?php
include '../../config/dbConnection.php';

$data = json_decode(file_get_contents('php://input'), true);

$items = $data['items'];
$payment = $data['payment'];

try {
    $conn->beginTransaction();

    // Insert transaction master
    $stmt = $conn->prepare("INSERT INTO transactions (total, tendered, change_amount, payment_type, trans_date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$payment['total'], $payment['tendered'], $payment['change'], $payment['transType']]);

    $trans_id = $conn->lastInsertId();

    // Insert each item
    $stmtItem = $conn->prepare("INSERT INTO transaction_items (trans_id, product_name, size, quantity, base_price, addons_price, total_price, addons, mods) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmtItem->execute([
            $trans_id,
            $item['name'],
            $item['size'],
            $item['qty'],
            $item['base'],
            $item['addonsTotal'],
            $item['total'],
            $item['addons'],
            $item['mods']
        ]);
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
