<?php
$host = "localhost";
$port = 3307;
$dbName = "smart_pos";
$username = "root";
$password = "";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Handle AJAX submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Create a new REG_TRANSACTION
    $stmt = $conn->prepare("INSERT INTO REG_TRANSACTION (STAFF_ID, TOTAL_AMOUNT) VALUES (?, ?)");
    $stmt->execute([1, 0]); // STAFF_ID=1 for testing
    $transaction_id = $conn->lastInsertId();

    $totalAmount = 0;

    foreach ($data['items'] as $item) {
        // Get price from size
        $stmtPrice = $conn->prepare("SELECT regular_price FROM product_sizes WHERE size_id=?");
        $stmtPrice->execute([$item['size_id']]);
        $price = $stmtPrice->fetch(PDO::FETCH_ASSOC)['regular_price'];

        $stmtItem = $conn->prepare("INSERT INTO transaction_item (REG_TRANSACTION_ID, PRODUCT_ID, SIZE_ID, QUANTITY, PRICE)
                                    VALUES (?, ?, ?, ?, ?)");
        $stmtItem->execute([$transaction_id, $item['product_id'], $item['size_id'], $item['quantity'], $price]);
        $item_id = $conn->lastInsertId();

        $totalAmount += $price * $item['quantity'];

        // Add-ons
        if (!empty($item['add_ons'])) {
            $stmtAddOn = $conn->prepare("INSERT INTO item_add_ons (add_ons_id, item_id) VALUES (?, ?)");
            foreach ($item['add_ons'] as $add_on_id) {
                $stmtAddOn->execute([$add_on_id, $item_id]);
            }
        }

        // Modifications
        if (!empty($item['modifications'])) {
            $stmtMod = $conn->prepare("INSERT INTO item_modification (item_id, modification_id) VALUES (?, ?)");
            foreach ($item['modifications'] as $mod_id) {
                $stmtMod->execute([$item_id, $mod_id]);
            }
        }
    }

    // Update total amount
    $stmtUpdate = $conn->prepare("UPDATE REG_TRANSACTION SET TOTAL_AMOUNT=? WHERE REG_TRANSACTION_ID=?");
    $stmtUpdate->execute([$totalAmount, $transaction_id]);

    echo json_encode(['success' => true, 'transaction_id' => $transaction_id]);
    exit;
}

// Fetch products + sizes
$products = $conn->query("SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size_id, ps.size, ps.regular_price
                          FROM product_details pd
                          JOIN product_sizes ps ON pd.product_id = ps.product_id
                          WHERE pd.status='active'
                          ORDER BY pd.product_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch add-ons
$addOns = $conn->query("SELECT * FROM PRODUCT_ADD_ONS WHERE status='active'")->fetchAll(PDO::FETCH_ASSOC);

// Fetch modifications
$mods = $conn->query("SELECT * FROM PRODUCT_MODIFICATIONS WHERE status='active'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>POS Test Modal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6">

    <button id="openModal" class="bg-blue-500 text-white p-2 rounded">Open Product Modal</button>

    <div id="productModal" class="hidden fixed inset-0 bg-black/50 flex justify-center items-start pt-20">
        <div class="bg-white p-4 rounded w-96 max-h-[80vh] overflow-y-auto">
            <form id="productForm">
                <div class="grid gap-4">
                    <?php foreach ($products as $p): ?>
                        <div class="border p-2 flex flex-col">
                            <img src="<?= $p['thumbnail_path'] ?>" class="w-24 h-24 object-cover" />
                            <h3><?= $p['product_name'] ?></h3>

                            <label>Size:
                                <select name="size_id" data-product-id="<?= $p['product_id'] ?>">
                                    <option value="<?= $p['size_id'] ?>"><?= $p['size'] ?> - <?= $p['regular_price'] ?></option>
                                </select>
                            </label>

                            <label>Quantity:
                                <input type="number" name="quantity" min="1" value="1" data-product-id="<?= $p['product_id'] ?>" />
                            </label>

                            <label>Add-Ons:
                                <select multiple name="add_ons[]" data-product-id="<?= $p['product_id'] ?>">
                                    <?php foreach ($addOns as $a): ?>
                                        <option value="<?= $a['ADD_ONS_ID'] ?>"><?= $a['ADD_ONS_NAME'] ?> - <?= $a['PRICE'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>

                            <label>Modifications:
                                <select multiple name="modifications[]" data-product-id="<?= $p['product_id'] ?>">
                                    <?php foreach ($mods as $m): ?>
                                        <option value="<?= $m['MODIFICATION_ID'] ?>"><?= $m['MODIFICATION_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>

                            <input type="checkbox" name="select_product" data-product-id="<?= $p['product_id'] ?>" /> Select
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="mt-4 bg-green-500 text-white p-2 rounded w-full">Add to Transaction</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('openModal').addEventListener('click', () => {
            document.getElementById('productModal').classList.remove('hidden');
        });

        document.getElementById('productForm').addEventListener('submit', e => {
            e.preventDefault();

            const selectedItems = [];
            document.querySelectorAll('input[name="select_product"]:checked').forEach(checkbox => {
                const pid = checkbox.dataset.productId;
                const size_id = document.querySelector(`select[name="size_id"][data-product-id="${pid}"]`).value;
                const qty = document.querySelector(`input[name="quantity"][data-product-id="${pid}"]`).value;
                const add_ons = Array.from(document.querySelector(`select[name="add_ons[]"][data-product-id="${pid}"]`).selectedOptions).map(o => o.value);
                const mods = Array.from(document.querySelector(`select[name="modifications[]"][data-product-id="${pid}"]`).selectedOptions).map(o => o.value);

                selectedItems.push({
                    product_id: pid,
                    size_id,
                    quantity: qty,
                    add_ons,
                    modifications: mods
                });
            });

            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        alert('Transaction created! ID: ' + resp.transaction_id);
                        document.getElementById('productModal').classList.add('hidden');
                    } else {
                        alert('Error adding transaction');
                    }
                });
        });
    </script>
</body>

</html>