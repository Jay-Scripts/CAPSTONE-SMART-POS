<?php
// =======================================================
// =     Fetching of IcedCoffee Products - Starts Here    =
// =======================================================

// --------------------------------------------------------------------------
// - SQL query selects product details for 'medio' size IcedCoffee products  -
// --------------------------------------------------------------------------
$sql = "
    SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
    FROM product_details pd
    JOIN product_sizes ps ON pd.product_id = ps.product_id
    WHERE pd.category_id = 2
    AND pd.status = 'active'
    AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
    ORDER BY pd.product_name ASC;
";

// ---------------------------------------------------------
// -   Code below is for grande price version of fetching  -
// ---------------------------------------------------------

// $sql = "
//     SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
//     FROM product_details pd
//     JOIN product_sizes ps ON pd.product_id = ps.product_id
//     WHERE pd.category_id = 6
//     AND pd.status = 'active'
//     AND ps.size = 'grande'
//     ORDER BY pd.product_name ASC;
// ";

// ------------------------------------------
// - Prepare and execute the SQL statement  -
// ------------------------------------------
$stmt = $conn->prepare($sql);
$stmt->execute();

// -----------------------------------------------------------
// -  Fetch each product row and build an associative array  -
// -----------------------------------------------------------
$products = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['product_id'];
    $products[$id] = [
        'name'      => $row['product_name'],
        'thumbnail' => $row['thumbnail_path'],
        'sizes'     => [$row['size'] => $row['regular_price']]
    ];
}

// =======================================================
// =      Fetching of IcedCoffee Products - Ends Here     =
// =======================================================
?>

<section class="flex flex-wrap justify-center gap-2">
    <?php foreach ($products as $id => $product): ?>
        <div
            class="optionChoice cursor-pointer aspect-square w-[47%] sm:w-[15%] bg-[transparent] rounded-lg border-2 border-[var(--border-color)] relative shadow-md p-2"
            data-id="<?php echo htmlspecialchars($id); ?>"
            data-name="<?php echo htmlspecialchars(strtoupper($product['name'])); ?>"
            data-sizes='<?php echo htmlspecialchars(json_encode($product['sizes'])); ?>'>

            <img
                src="<?php echo htmlspecialchars($product['thumbnail']); ?>"
                alt="<?php echo htmlspecialchars(strtoupper($product['name'])); ?> IMAGE"
                class="object-cover rounded-t-lg" />

            <p class="text-center text-xs font-bold z-10 text-[var(--text-color)]">
                <?php echo htmlspecialchars(strtoupper($product['name'])); ?>
                <?php foreach ($product['sizes'] as $size => $price): ?>
                <?php endforeach; ?>
            </p>
        </div>
    <?php endforeach; ?>
</section>

<!-- ========== MODAL ========== -->
<div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 w-80 relative">
        <button id="closeModal" class="absolute top-2 right-3 text-gray-500 hover:text-red-600 text-lg font-bold">×</button>

        <h2 id="modalProductName" class="text-lg font-bold mb-3 text-center text-[var(--text-color)]">Product Name</h2>

        <div class="space-y-2 mb-4">
            <p class="text-sm font-semibold">Select Size:</p>
            <div class="flex gap-3 justify-center">
                <button class="size-btn bg-gray-200 hover:bg-gray-300 text-black px-3 py-1 rounded" data-size="medio">Medio</button>
                <button class="size-btn bg-gray-200 hover:bg-gray-300 text-black px-3 py-1 rounded" data-size="grande">Grande</button>
            </div>
        </div>

        <div class="space-y-2 mb-4">
            <p class="text-sm font-semibold">Add-ons:</p>
            <div class="flex flex-wrap gap-2 justify-center">
                <button class="addon-btn bg-gray-100 hover:bg-gray-200 text-black px-2 py-1 text-xs rounded" data-addon="Pearls">Pearls</button>
                <button class="addon-btn bg-gray-100 hover:bg-gray-200 text-black px-2 py-1 text-xs rounded" data-addon="Cream">Cream</button>
                <button class="addon-btn bg-gray-100 hover:bg-gray-200 text-black px-2 py-1 text-xs rounded" data-addon="Espresso Shot">Espresso Shot</button>
            </div>
        </div>

        <button id="confirmSelection" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">Confirm</button>
    </div>
</div>