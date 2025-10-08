<?php
// =======================================================
// =     Fetching of IcedCoffee Products - Starts Here    =
// =======================================================

// --------------------------------------------------------------------------
// - SQL query selects product details for 'medio' size IcedCoffee products  -
// --------------------------------------------------------------------------
$sql = "
SELECT
  pd.product_id,
  pd.product_name,
  pd.thumbnail_path,
  COALESCE(ps_medio.regular_price, 0) AS medio_price,
  COALESCE(ps_grande.regular_price, 0) AS grande_price
FROM product_details pd
LEFT JOIN product_sizes ps_medio
  ON pd.product_id = ps_medio.product_id AND ps_medio.size = 'medio'
LEFT JOIN product_sizes ps_grande
  ON pd.product_id = ps_grande.product_id AND ps_grande.size = 'grande'
WHERE pd.category_id = 1
  AND pd.status = 'active'
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

<section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    <?php foreach ($products as $id => $product): ?>
        <div
            class="optionChoice cursor-pointer m-2 bg-[transparent] rounded-lg border-2 border-[var(--border-color)] relative shadow-md"
            data-id="<?php echo htmlspecialchars($id); ?>"
            data-name="<?php echo htmlspecialchars(strtoupper($product['name'])); ?>"
            data-sizes='<?php echo htmlspecialchars(json_encode($product['sizes'])); ?>'>

            <img
                src="<?php echo htmlspecialchars($product['thumbnail']); ?>"
                alt="<?php echo htmlspecialchars(strtoupper($product['name'])); ?> IMAGE"
                class="w-full h-auto object-cover rounded-t-lg" />

            <p class="text-center text-[8px] sm:text-[9px] lg:text-[10px] font-bold mb-2 z-10 text-[var(--text-color)]">
                <?php echo htmlspecialchars(strtoupper($product['name'])); ?>
                <?php foreach ($product['sizes'] as $size => $price): ?>
                    <span class="text-red-500">
                        <?php echo htmlspecialchars(strtoupper(substr($size, 0, 1))); ?>: ₱<?php echo htmlspecialchars(number_format($price, 2)); ?>
                    </span>
                <?php endforeach; ?>
            </p>
        </div>
    <?php endforeach; ?>
</section>