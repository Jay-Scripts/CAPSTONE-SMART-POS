<?php

$sql = "SELECT product_name, medio_price FROM milktea WHERE status = 'active'";
$result = $conn->query($sql);

// 3. Loop and render
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product = $row['product_name'];
        $price = number_format($row['medio_price'], 2);
        $imageName = strtolower(str_replace(" ", "%20", $product)); // convert to image filename

        echo '
        <div 
            class="optionChoice cursor-pointer m-2 bg-[transparent] rounded-lg border-1 border-[var(--border-color)] relative shadow-md"
            data-product="' . htmlspecialchars($product) . '"
            data-size="medio"
            data-price="' . $row['medio_price'] . '"
        >
            <img
                src="../assets/IMAGES/MENU IMAGES/MILKTEA_MENU/' . $imageName . '.png"
                alt="' . strtoupper($product) . ' IMAGE"
                class="w-full h-auto object-cover rounded-t-lg"
            />
            <p class="text-center text-[8px] sm:text-[9px] lg:text-[10px] font-bold mb-2 z-10 text-[var(--text-color)]">
                ' . strtoupper($product) . '<br>₱' . $price . '
            </p>
        </div>';
    }
} else {
    echo "<p>No milk tea products found.</p>";
}

$conn->close();
