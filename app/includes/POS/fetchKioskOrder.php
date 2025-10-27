<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');
session_start(); // ✅ Make sure sessions are enabled

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing transaction ID']);
    exit;
}

$transactionId = intval($_GET['id']);

try {
    // ✅ Fetch main transaction (only if status = 'Pending')
    $stmt = $conn->prepare("SELECT * FROM kiosk_transaction WHERE kiosk_transaction_id = ? AND status = 'Pending'");
    $stmt->execute([$transactionId]);
    $txn = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$txn) {
        echo json_encode(['success' => false, 'message' => 'Pending transaction not found']);
        exit;
    }

    // ✅ Store kiosk_transaction_id to session
    $_SESSION['kiosk_transaction_id'] = $txn['kiosk_transaction_id'];

    // ✅ Fetch ordered items + add-ons + modifications
    $q = "
        SELECT 
            ki.item_id,
            ki.product_id,
            ki.size_id,
            ki.quantity,
            ki.price,
            COALESCE(ka.addon_ids, '[]') AS addon_ids,
            COALESCE(km.modification_ids, '[]') AS modification_ids
        FROM kiosk_transaction_item ki
        LEFT JOIN (
            SELECT 
                kiosk_item_id,
                CONCAT('[', GROUP_CONCAT(add_ons_id SEPARATOR ','), ']') AS addon_ids
            FROM kiosk_item_addons
            GROUP BY kiosk_item_id
        ) ka ON ki.item_id = ka.kiosk_item_id
        LEFT JOIN (
            SELECT 
                kiosk_item_id,
                CONCAT('[', GROUP_CONCAT(modification_id SEPARATOR ','), ']') AS modification_ids
            FROM kiosk_item_modification
            GROUP BY kiosk_item_id
        ) km ON ki.item_id = km.kiosk_item_id
        WHERE ki.kiosk_transaction_id = ?
    ";

    $itemsStmt = $conn->prepare($q);
    $itemsStmt->execute([$transactionId]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'transaction' => $txn,
        'items' => $items,
        'session_kiosk_id' => $_SESSION['kiosk_transaction_id'] // ✅ for verification
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
