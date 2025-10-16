<?php
include "../../app/config/dbConnection.php";

if (!isset($category_id)) {
    echo "<p class='text-red-500'>Category not specified.</p>";
    return;
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
        <div class="optionChoice relative aspect-square w-[47%] sm:w-[15%] bg-transparent rounded-lg border border-gray-400 p-2">
            <img src="<?= $product['thumbnail_path'] ?>" class="object-cover w-full h-[80%] rounded-md">
            <h3 class="text-center text-[var(--text-color)] font-semibold"><?= htmlspecialchars($product['product_name']) ?></h3>

            <!-- Disable Button -->
            <button
                class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white text-xs px-2 py-1 rounded"
                onclick="enableProduct(<?= $product['product_id'] ?>, event)">
                Disable
            </button>
        </div>
    <?php endforeach; ?>
</section>

<script>
    function enableProduct(productId, event) {
        event.stopPropagation();

        Swal.fire({
            title: "Disable Product?",
            text: "Are you sure you want to disable this product?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, disable it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('../../app/includes/managerModule/disableProduct.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'product_id=' + productId
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // ✅ Remove the product card from the UI instantly
                            const productCard = event.target.closest('.optionChoice');
                            productCard.style.opacity = '0';
                            setTimeout(() => productCard.remove(), 300);

                            Swal.fire({
                                title: "Disabled!",
                                text: data.message,
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire("Error!", data.message, "error");
                        }
                    })
                    .catch(() => {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    });
            }
        });
    }
</script>