<!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Add Stocks Starts Here                                                        =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-5">
    <h2 class="text-xl sm:text-2xl font-bold">Inventory Products</h2>
    <button id="openModalBtn" class="flex items-center gap-2 rounded-lg bg-green-600 text-white px-5 py-2 text-sm font-semibold shadow hover:bg-green-700 hover:scale-105 transition-transform duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
        <!-- Plus icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Inventory Items
    </button>

</div>
<!-- 
      ==========================================================================================================================================
      =                                                   Modal Adding                                                                         =
      ==========================================================================================================================================
    -->
<div id="inventoryModal" class="fixed inset-0 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="relative bg-[var(--background-color)] text-[var(--text-color)] border border-[var(--border-color)] rounded-2xl shadow-xl w-2xl  p-6 animate-[fadeIn_0.3s_ease]">
        <h2 class="text-lg sm:text-xl font-bold mb-4 text-center ">Receive Inventory</h2>
        <form id="inventoryForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="item_name">Item Name</label>
                <input type="text" id="item_name" required class="w-full p-2  rounded-lg bg-[var(--background-color)] border border-[var(--border-color)" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="inv_category">Inventory Category</label>
                <select id="inv_category" required class="w-full p-2 border rounded-lg bg-[var(--background-color)]  border-[var(--border-color)">
                    <option value="" disabled selected>Select inventory category</option>
                    <?php foreach ($invCategories as $cat): ?>
                        <option value="<?= $cat['inv_category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="prod_category">Product Category</label>
                <select id="prod_category" class="w-full p-2 border rounded-lg bg-[var(--background-color)]  border-[var(--border-color)">
                    <option value="" disabled selected>Select product category</option>
                    <?php foreach ($prodCategories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="product">Product</label>
                <select id="product" class="w-full p-2 border rounded-lg bg-[var(--background-color)]  border-[var(--border-color)">
                    <option value="" disabled selected>Select product</option>
                    <?php foreach ($products as $prod): ?>
                        <option value="<?= $prod['product_id'] ?>"><?= htmlspecialchars($prod['product_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1" for="quantity">Quantity</label>
                    <input type="number" id="quantity" required min="1" class="w-full p-2 border rounded-lg bg-[var(--background-color)]  border-[var(--border-color)" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="unit">Unit</label>
                    <select id="unit" class="w-full p-2 border rounded-lg bg-[var(--background-color)]  border-[var(--border-color)">
                        <option value="pcs">Pieces (pcs)</option>
                        <option value="g">Grams (g)</option>
                        <option value="ml">Milliliters (ml)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium mb-1" for="date_made">Date Made</label>
                    <input type="date" id="date_made" required class="w-full p-2  rounded-lg bg-[var(--background-color)] border border-[var(--border-color)" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" for="date_expiry">Expiry Date</label>
                    <input type="date" id="date_expiry" required class="w-full p-2  rounded-lg bg-[var(--background-color)] border border-[var(--border-color)" />
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" id="closeModalBtn" class="bg-gray-200 px-4 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Add Stocks Ends Here                                                          =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->