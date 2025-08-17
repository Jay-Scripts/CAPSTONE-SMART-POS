<?php

// =======================================================
// =     Fetching of Brosty Products - Starts Here      =
// =======================================================

// Class BrostyMenu extends Dbh to access database connection methods
class BrostyMenu extends Dbh
{
    // -------------------------------------------------------
    // -   Retrieves milk tea products from the database     -
    // -------------------------------------------------------
    public function getBrostyProducts()
    {
        // --------------------------------------------------------------------------
        // - SQL query selects product details for 'medio' size brosty products   -
        // --------------------------------------------------------------------------
        $sql = "
       SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
            FROM product_details pd
            JOIN product_sizes ps ON pd.product_id = ps.product_id
            WHERE pd.category_id = 5
            AND pd.status = 'active'
			AND ps.size = 'medio' -- SET THIS TO GRANDE FOR GRANDE SIZE 
            ORDER BY pd.product_name ASC;
        ";

        // ---------------------------------------------------------
        // -   Code below is for grande price version of fetching  -
        // ---------------------------------------------------------

        //     $sql = "
        //    SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
        //         FROM product_details pd
        //         JOIN product_sizes ps ON pd.product_id = ps.product_id
        //         WHERE pd.category_id = 5
        //         AND pd.status = 'active'
        // 		   AND ps.size = 'grande' 
        //         ORDER BY pd.product_name ASC;
        //     ";



        // ------------------------------------------
        // - Prepare and execute the SQL statement  -
        // ------------------------------------------

        $stmt = $this->getConnection()->prepare($sql);
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
        // Return the array of products
        return $products;
    }
}

// Instantiate the BrostyMenu class and fetch products
$menu = new BrostyMenu();
$products = $menu->getBrostyProducts();

// =======================================================
// =      Fetching of Brosty Products - Ends Here       =
// =======================================================

?>

<section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    <?php foreach ($products as $id => $product): ?>

        <!--
                ----------------------- 
                - Product Container   -
                -----------------------
            -->
        <div
            class="optionChoice cursor-pointer m-2 bg-[transparent] rounded-lg border-2 border-[var(--border-color)] relative shadow-md"
            data-id="<?php echo htmlspecialchars($id); ?>"
            data-name="<?php echo htmlspecialchars(strtoupper($product['name'])); ?>"
            data-sizes='<?php echo htmlspecialchars(json_encode($product['sizes'])); ?>'>
            <!--
                ---------------------------- 
                - Product thumbnail image  -
                ---------------------------- 
            -->
            <img
                src="<?php echo htmlspecialchars($product['thumbnail']); ?>"
                alt="<?php echo htmlspecialchars(strtoupper($product['name'])); ?> IMAGE"
                class="w-full h-auto object-cover rounded-t-lg" />
            <!--
                ---------------------------------- 
                - Product name and price display -
                ----------------------------------
            -->
            <p
                class="text-center text-[8px] sm:text-[9px] lg:text-[10px] font-bold mb-2 z-10 text-[var(--text-color)]">
                <?php echo htmlspecialchars(strtoupper($product['name'])); ?> <?php foreach ($product['sizes'] as $size => $price): ?>
                    <span class="text-red-500"> <?php echo htmlspecialchars(strtoupper(substr($size, 0, 1))); ?>: ₱<?php echo htmlspecialchars(number_format($price, 2)); ?>
                    </span>
                <?php endforeach; ?>
            </p>
        </div>
    <?php endforeach; ?>
</section>