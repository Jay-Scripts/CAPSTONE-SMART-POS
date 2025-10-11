<?php
include "../../config/dbConnection.php";
session_start();

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || empty($data['order_data'])) {
        echo json_encode(["success" => false, "message" => "No order data received"]);
        exit;
    }

    $staff_id = $_SESSION['staff_id'] ?? 1;
    $total = $data['total'] ?? 0;
    $tendered = $data['tendered'] ?? 0;
    $change = $data['change'] ?? 0;
    $payment_type = $data['payment_type'] ?? 'CASH';
    $orders = $data['order_data'];

    $conn->beginTransaction();

    // 1️⃣ Insert transaction summary
    $stmtTrans = $conn->prepare("
        INSERT INTO REG_TRANSACTION (staff_id, total_amount)
        VALUES (:staff_id, :total)
    ");
    $stmtTrans->execute([
        ':staff_id' => $staff_id,
        ':total' => $total
    ]);
    $transaction_id = $conn->lastInsertId();

    // 2️⃣ Insert each order item
    $stmtItem = $conn->prepare("
        INSERT INTO transaction_item 
        (REG_TRANSACTION_ID, PRODUCT_ID, SIZE_ID, QUANTITY, PRICE)
        VALUES (:transaction_id, :product_id, :size_id, :qty, :price)
    ");

    // Add-ons & mods
    $stmtAddOn = $conn->prepare("
        INSERT INTO item_add_ons (add_ons_id, item_id)
        VALUES (:addon_id, :item_id)
    ");
    $stmtMod = $conn->prepare("
        INSERT INTO item_modification (item_id, modification_id)
        VALUES (:item_id, :mod_id)
    ");

    foreach ($orders as $order) {
        $stmtItem->execute([
            ':transaction_id' => $transaction_id,
            ':product_id' => $order['product_id'], // must send product_id from frontend
            ':size_id' => $order['size_id'],       // must send size_id from frontend
            ':qty' => $order['qty'] ?? 1,
            ':price' => $order['base'] + $order['addonsTotal']
        ]);
        $item_id = $conn->lastInsertId();

        if (!empty($order['addons'])) {
            foreach ($order['addons'] as $addonId) {
                $stmtAddOn->execute([
                    ':addon_id' => $addonId,
                    ':item_id' => $item_id
                ]);
            }
        }

        if (!empty($order['mods'])) {
            foreach ($order['mods'] as $modId) {
                $stmtMod->execute([
                    ':item_id' => $item_id,
                    ':mod_id' => $modId
                ]);
            }
        }
    }

    // 3️⃣ Insert payment
    $stmtPayment = $conn->prepare("
        INSERT INTO PAYMENT_METHODS 
        (REG_TRANSACTION_ID, TYPE, AMOUNT_SENT, CHANGE_AMOUNT)
        VALUES (:transaction_id, :type, :amount_sent, :change_amount)
    ");
    $stmtPayment->execute([
        ':transaction_id' => $transaction_id,
        ':type' => $payment_type,
        ':amount_sent' => $tendered,
        ':change_amount' => $change
    ]);

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Transaction saved successfully"]);
} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
