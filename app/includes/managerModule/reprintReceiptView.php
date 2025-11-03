<?php
include "../../config/dbConnection.php";

if (!isset($_GET['id'])) die("No transaction ID provided.");
$transaction_id = intval($_GET['id']);

try {
    $stmt = $conn->prepare("
    SELECT si.staff_name AS cashier, rt.total_amount, rt.vat_amount, rt.date_added
    FROM reg_transaction rt
    JOIN staff_info si ON rt.staff_id = si.staff_id
    WHERE rt.reg_transaction_id = :id
  ");
    $stmt->execute([':id' => $transaction_id]);
    $receipt = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$receipt) die("Receipt not found.");

    $pay = $conn->prepare("SELECT type, amount_sent, change_amount FROM payment_methods WHERE reg_transaction_id = :id LIMIT 1");
    $pay->execute([':id' => $transaction_id]);
    $p = $pay->fetch(PDO::FETCH_ASSOC);
    $type = $p['type'] ?? 'CASH';
    $tendered = $p['amount_sent'] ?? 0;
    $change = $p['change_amount'] ?? 0;

    $disc = $conn->prepare("SELECT ID_TYPE, FIRST_NAME, LAST_NAME, DISC_TOTAL_AMOUNT FROM disc_transaction WHERE REG_TRANSACTION_ID = :id LIMIT 1");
    $disc->execute([':id' => $transaction_id]);
    $d = $disc->fetch(PDO::FETCH_ASSOC);
    $disc_amt = $d['DISC_TOTAL_AMOUNT'] ?? 0;

    $items = $conn->prepare("
    SELECT ti.item_id, pd.product_name, ps.size, ti.quantity, ti.price
    FROM transaction_item ti
    JOIN product_details pd ON ti.product_id = pd.product_id
    JOIN product_sizes ps ON ti.size_id = ps.size_id
    WHERE ti.reg_transaction_id = :id
  ");
    $items->execute([':id' => $transaction_id]);
    $rows = $items->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $k => $i) {
        $add = $conn->prepare("SELECT ao.add_ons_name, ao.price FROM item_add_ons ia JOIN product_add_ons ao ON ia.add_ons_id=ao.add_ons_id WHERE ia.item_id=:id");
        $add->execute([':id' => $i['item_id']]);
        $rows[$k]['addons'] = $add->fetchAll(PDO::FETCH_ASSOC);
        $mod = $conn->prepare("SELECT pm.modification_name FROM item_modification im JOIN product_modifications pm ON im.modification_id=pm.modification_id WHERE im.item_id=:id");
        $mod->execute([':id' => $i['item_id']]);
        $rows[$k]['mods'] = $mod->fetchAll(PDO::FETCH_ASSOC);
    }

    $t = $conn->prepare("SELECT SUM(quantity) FROM transaction_item WHERE reg_transaction_id=:id");
    $t->execute([':id' => $transaction_id]);
    $total_items = $t->fetchColumn() ?? 0;
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        header,
        footer {
            text-align: center;
        }

        body {
            font-family: "Courier New", monospace;
            width: 80mm;
            margin: 0 auto;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            font-size: 13px;
            vertical-align: top;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .addons,
        .mods {
            text-align: left;
            white-space: pre-line;
        }

        hr {
            border: 1px dashed black;
        }
    </style>
</head>

<body onload="window.print();setTimeout(()=>window.close(),1500)">
    <header>
        <h3>BIG BREW POS</h3>
        <p>Sta. Mesa Manila Branch<br>smartposBBstamesa@gmail.com</p>
        <p>Txn#: <?= str_pad($transaction_id, 6, '0', STR_PAD_LEFT) ?><br>
            <?= date('Y-m-d h:i A', strtotime($receipt['date_added'])) ?><br>
            Cashier: <?= $receipt['cashier'] ?><br>Payment: <?= $type ?></p>
    </header>
    <hr>

    <table>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td colspan="2"><?= $r['quantity'] ?>x <?= htmlspecialchars($r['product_name']) ?> (<?= $r['size'] ?>)</td>
                <td>₱<?= number_format($r['price'] * $r['quantity'], 2) ?></td>
            </tr>
            <?php if (!empty($r['addons'])): ?>
                <tr>
                    <td colspan="3" style="padding-left:10px;font-size:11px">
                        <?php foreach ($r['addons'] as $a): ?>+<?= $a['add_ons_name'] ?> ₱<?= number_format($a['price'], 2) ?><br><?php endforeach; ?>
                    </td>
                </tr><?php endif; ?>
            <?php if (!empty($r['mods'])): ?>
                <tr>
                    <td colspan="3" style="padding-left:10px;font-size:11px">
                        <?php foreach ($r['mods'] as $m): ?>*<?= $m['modification_name'] ?><br><?php endforeach; ?>
                    </td>
                </tr><?php endif; ?>
        <?php endforeach; ?>
    </table>

    <hr>
    <p class="total">Items: <?= $total_items ?></p>
    <p class="total">Vatable: ₱<?= number_format($receipt['total_amount'] - $receipt['vat_amount'], 2) ?></p>
    <p class="total">VAT (12%): ₱<?= number_format($receipt['vat_amount'], 2) ?></p>
    <p class="total">Total: ₱<?= number_format($receipt['total_amount'], 2) ?></p>
    <?php if ($disc_amt > 0): ?><p class="total">Less Disc: ₱<?= number_format($disc_amt, 2) ?></p><?php endif; ?>
    <p class="total">Cash: ₱<?= number_format($tendered, 2) ?></p>
    <p class="total">Change: ₱<?= number_format($change, 2) ?></p>

    <footer>
        <hr>
        <?php if (!empty($d)): ?><p><?= htmlspecialchars($d['ID_TYPE']) ?>: <?= htmlspecialchars($d['FIRST_NAME'] . ' ' . $d['LAST_NAME']) ?></p><?php endif; ?>
        <p>Thank you for purchasing!<br>fb.com/BigBrewStaMesaManila</p>
    </footer>
</body>

</html>