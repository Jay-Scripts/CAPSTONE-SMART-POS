<?php
include "../../config/dbConnection.php";

$category_id = $_GET['category_id'] ?? null;
if (!$category_id) {
    echo "<p class='text-red-500'>Category not specified.</p>";
    exit;
}

$stmt = $conn->prepare("
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size_id, ps.size, ps.regular_price
FROM product_details pd
JOIN product_sizes ps ON pd.product_id = ps.product_id
WHERE pd.category_id = ?
AND pd.status = 'active'
ORDER BY pd.product_name ASC
");
$stmt->execute([$category_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];
foreach ($rows as $row) {
    $id = $row['product_id'];
    if (!isset($products[$id])) {
        $products[$id] = [
            'product_id' => $id,
            'product_name' => $row['product_name'],
            'thumbnail_path' => $row['thumbnail_path'],
            'sizes' => []
        ];
    }
    $products[$id]['sizes'][] = [
        'size_id' => $row['size_id'],
        'size' => $row['size'],
        'price' => $row['regular_price']
    ];
}
?>

<section class="flex flex-wrap justify-center gap-2">
    <?php foreach ($products as $product): ?>
        <div class="optionChoice cursor-pointer aspect-square w-[47%] sm:w-[15%] bg-transparent rounded-lg border border-gray-400 p-2"
            data-product-id="<?= $product['product_id'] ?>"
            onclick='openModal(<?= json_encode($product) ?>)'>
            <img src="<?= $product['thumbnail_path'] ?>" class="object-cover">
            <h3 class="text-center text-[var(--text-color)] font-semibold">
                <?= htmlspecialchars($product['product_name']) ?>
            </h3>
        </div>
    <?php endforeach; ?>
</section>