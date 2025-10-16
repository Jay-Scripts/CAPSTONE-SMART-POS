const categories = [
  {
    id: 1,
    target: "milkteaMenu",
  },
  {
    id: 2,
    target: "fruitTeaMenu",
  },
  {
    id: 3,
    target: "hotBrewMenu",
  },
  {
    id: 4,
    target: "prafMenu",
  },
  {
    id: 5,
    target: "brostyMenu",
  },
  {
    id: 6,
    target: "icedCoffeeMenu",
  },
  {
    id: 7,
    target: "promosMenu",
  },
];

function loadProducts(categoryId, targetId) {
  fetch(
    `../../app/includes/POS/POSRealTimeProductCheckStatus.php?category_id=${categoryId}`
  )
    .then((res) => res.text())
    .then((html) => {
      document.getElementById(targetId).innerHTML = html;
    })
    .catch((err) => console.error(`Sync failed for ${targetId}:`, err));
}

// Initial load
categories.forEach((c) => loadProducts(c.id, c.target));

// Real-time auto-sync every 1.5s
setInterval(() => {
  categories.forEach((c) => loadProducts(c.id, c.target));
}, 1500);
