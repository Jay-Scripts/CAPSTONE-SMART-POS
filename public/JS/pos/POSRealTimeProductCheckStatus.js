const categories = [
  { id: 1, target: "milkteaMenu" },
  { id: 2, target: "fruitTeaMenu" },
  { id: 3, target: "hotBrewMenu" },
  { id: 4, target: "prafMenu" },
  { id: 5, target: "brostyMenu" },
  { id: 6, target: "icedCoffeeMenu" },
  { id: 7, target: "promosMenu" },
];

async function loadProducts(categoryId, targetId) {
  try {
    const response = await fetch(
      `../../app/includes/POS/POSRealTimeProductCheckStatus.php?category_id=${categoryId}`
    );
    const html = await response.text();
    document.getElementById(targetId).innerHTML = html;
  } catch (error) {
    console.error(`Sync failed for ${targetId}:`, error);
  }
}

// Initial load
categories.forEach((c) => loadProducts(c.id, c.target));

// Real-time auto-sync every 1.5 seconds
setInterval(() => {
  categories.forEach((c) => loadProducts(c.id, c.target));
}, 1500);
