<?php
session_start();
header('Content-Type: application/json');
include "../../config/dbConnection.php";


// Check session first
if (!isset($_SESSION['staff_name'])) {
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

try {
    $selecteQueryForPaidTrans = "
    SELECT 
        rt.REG_TRANSACTION_ID,
        rt.STATUS,
        rt.TOTAL_AMOUNT,
        UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(rt.date_added) AS elapsed_seconds,
        ti.ITEM_ID,
        ti.QUANTITY,
        ti.PRICE,
        pd.product_name,
        ps.SIZE AS size_name
    FROM REG_TRANSACTION rt
    JOIN TRANSACTION_ITEM ti ON rt.REG_TRANSACTION_ID = ti.REG_TRANSACTION_ID
    JOIN PRODUCT_DETAILS pd ON ti.PRODUCT_ID = pd.PRODUCT_ID
    JOIN PRODUCT_SIZES ps ON ti.SIZE_ID = ps.SIZE_ID
    WHERE rt.STATUS = 'PAID'
    ORDER BY rt.date_added DESC, ti.ITEM_ID ASC
    ";

    $stmt = $conn->query($selecteQueryForPaidTrans);

    $transactions = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $regId = $row['REG_TRANSACTION_ID'];

        if (!isset($transactions[$regId])) {
            $transactions[$regId] = [
                'REG_TRANSACTION_ID' => $regId,
                'status' => $row['STATUS'],
                'total_amount' => $row['TOTAL_AMOUNT'],
                'elapsed_seconds' => $row['elapsed_seconds'],
                'items' => []
            ];
        }

        $itemId = $row['ITEM_ID'];

        // Add-ons
        $addonsStmt = $conn->prepare("
            SELECT pa.add_ons_name
            FROM item_add_ons ia
            JOIN product_add_ons pa ON ia.add_ons_id = pa.add_ons_id
            WHERE ia.item_id = :itemId
        ");
        $addonsStmt->execute(['itemId' => $itemId]);
        $addons = $addonsStmt->fetchAll(PDO::FETCH_COLUMN);

        // Modifications
        $modsStmt = $conn->prepare("
            SELECT pm.modification_name
            FROM item_modification im
            JOIN product_modifications pm ON im.modification_id = pm.modification_id
            WHERE im.item_id = :itemId
        ");
        $modsStmt->execute(['itemId' => $itemId]);
        $mods = $modsStmt->fetchAll(PDO::FETCH_COLUMN);

        $transactions[$regId]['items'][] = [
            'quantity' => $row['QUANTITY'],
            'product_name' => $row['product_name'],
            'size' => $row['size_name'],
            'price' => $row['PRICE'],
            'addons' => $addons,
            'mods' => $mods
        ];
    }

    // Return transactions as JSON
    echo json_encode(array_values($transactions));
} catch (Exception $e) {
    // Always return JSON on error
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
