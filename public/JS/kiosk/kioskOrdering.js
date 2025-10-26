function kioskCheckout() {
  if (cart.length === 0) {
    Swal.fire({
      icon: "warning",
      title: "Empty Cart",
      text: "Add items before placing order.",
    });
    return;
  }

  const formData = new FormData();
  formData.append("order_data", JSON.stringify(cart));
  formData.append("total", originalTotal);

  // 👉 Make sure this points to your kiosk PHP file
  fetch("../../app/includes/KIOSK/kioskCheckout.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((text) => {
      console.log("Raw response:", text);
      if (!text || text.trim() === "") throw new Error("Empty response");

      const data = JSON.parse(text);

      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "Order Placed!",
          text: `Kiosk Transaction #${data.transaction_id}`,
          timer: 2000,
          showConfirmButton: false,
        }).then(() => {
          // 🧾 Print QR code receipt for kiosk order
          fetch(
            `../../app/includes/KIOSK/printKioskQR.php?id=${data.transaction_id}&type=kiosk`
          )
            .then((res) => res.text())
            .then((receiptHTML) => {
              const printWindow = window.open(
                "",
                "_blank",
                "width=400,height=600"
              );
              printWindow.document.write(receiptHTML);
              printWindow.document.close();
              printWindow.focus();
              printWindow.print();
            });

          cart = [];
          renderCart();
          closeCalculator();
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.message || "Order failed.",
        });
      }
    })
    .catch((err) => {
      console.error("Error:", err);
      Swal.fire({
        icon: "error",
        title: "Server Error",
        text: err.message,
      });
    });
}
