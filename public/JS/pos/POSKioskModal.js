/* ================================
       KIOSK & QR FUNCTIONS
       ================================ */
function openQrPopup() {
  document.getElementById("qrPopup").classList.remove("hidden");
}

function closeQrPopup() {
  document.getElementById("qrPopup").classList.add("hidden");
}

function openEPaymentPopup() {
  document.getElementById("EPaymentPopup").classList.remove("hidden");
}

function closeEPaymentPopup() {
  document.getElementById("EPaymentPopup").classList.add("hidden");
}

async function loadKioskOrder(transactionId) {
  try {
    const res = await fetch(
      `../../app/includes/POS/fetchKioskOrder.php?id=${transactionId}`
    );
    const data = await res.json();
    if (!data.success) {
      Swal.fire("Error", data.message, "error");
      return;
    }

    cart = data.items.map((item) => ({
      product_id: parseInt(item.product_id),
      size_id: parseInt(item.size_id),
      quantity: parseInt(item.quantity),
      price: parseFloat(item.price),
      addons: JSON.parse(item.addon_ids),
      modifications: JSON.parse(item.modification_ids),
    }));

    renderCart();
    Swal.fire({
      icon: "success",
      title: "Kiosk Order Loaded!",
      text: `Transaction #${transactionId} added to cart.`,
      timer: 1200,
      showConfirmButton: false,
    });
  } catch (err) {
    Swal.fire("Error", err.message, "error");
  }
}

function openKioskModal() {
  document.getElementById("kioskModal").classList.remove("hidden");
  document.getElementById("kioskInput").focus();
}

function closeKioskModal() {
  document.getElementById("kioskModal").classList.add("hidden");
  document.getElementById("kioskInput").value = "";
}

function submitKioskOrder() {
  let input = document.getElementById("kioskInput").value.trim();
  if (!input) {
    Swal.fire(
      "Input Required",
      "Please enter or scan a Kiosk QR code.",
      "warning"
    );
    return;
  }

  const match = input.match(/(\d+)/);
  if (!match) {
    Swal.fire(
      "Invalid Code",
      "Please scan a valid Kiosk QR (TXN-xxxxx).",
      "error"
    );
    return;
  }

  closeKioskModal();
  loadKioskOrder(match[1]);
}
