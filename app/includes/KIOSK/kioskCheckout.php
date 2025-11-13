<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
include "../../config/dbConnection.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    try {
        if (!($conn instanceof PDO)) {
            throw new Exception("Database connection not using PDO or failed.");
        }

        $orderData = json_decode($_POST['order_data'], true);
        $total = floatval($_POST['total']);

        // ✅ Basic validation
        if (empty($orderData)) {
            throw new Exception("Order data is empty.");
        }
        if ($total <= 0) {
            throw new Exception("Invalid total amount.");
        }

        // ✅ Insert main transaction
        $stmt = $conn->prepare("
            INSERT INTO kiosk_transaction (total_amount, status)
            VALUES ( ?, 'PENDING')
        ");
        $stmt->execute([$total]);
        $kioskId = $conn->lastInsertId();

        // ✅ Prepare item insert
        $stmtItem = $conn->prepare("
            INSERT INTO kiosk_transaction_item (kiosk_transaction_id, product_id, size_id, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");

        // ✅ Prepare add-on insert
        $stmtAddon = $conn->prepare("
            INSERT INTO kiosk_item_addons (add_ons_id, kiosk_item_id)
            VALUES (?, ?)
        ");

        // ✅ Prepare modification insert
        $stmtMod = $conn->prepare("
            INSERT INTO kiosk_item_modification (modification_id, kiosk_item_id)
            VALUES (?, ?)
        ");

        // ✅ Loop through each product in the order
        foreach ($orderData as $index => $item) {
            if (!isset($item['product_id'], $item['quantity'], $item['price'])) {
                throw new Exception("Missing fields in item #$index");
            }

            $productId = intval($item['product_id']);
            $sizeId = intval($item['size_id'] ?? 0);
            $quantity = intval($item['quantity']);
            $price = floatval($item['price']);

            if ($productId <= 0 || $quantity <= 0 || $price < 0) {
                throw new Exception("Invalid values in item #$index");
            }

            // Insert main item
            $stmtItem->execute([
                $kioskId,
                $productId,
                $sizeId ?: null,
                $quantity,
                $price
            ]);
            $kioskItemId = $conn->lastInsertId();

            // ✅ Add-ons (if present)
            if (!empty($item['addons']) && is_array($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $addonId = is_array($addon) ? intval($addon['add_ons_id'] ?? $addon['id'] ?? 0) : intval($addon);
                    if ($addonId > 0) {
                        $stmtAddon->execute([$addonId, $kioskItemId]);
                    }
                }
            }

            // ✅ Modifications (if present)
            if (!empty($item['modifications']) && is_array($item['modifications'])) {
                foreach ($item['modifications'] as $mod) {
                    $modId = is_array($mod) ? intval($mod['modification_id'] ?? $mod['id'] ?? 0) : intval($mod);
                    if ($modId > 0) {
                        $stmtMod->execute([$modId, $kioskItemId]);
                    }
                }
            }
        }

        echo json_encode([
            'success' => true,
            'transaction_id' => $kioskId
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
