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

fetch("", {
method: "POST",
body: formData,
})
.then((res) => res.json())
.then((data) => {
if (data.success) {
Swal.fire({
icon: "success",
title: "Order Placed!",
text: `Transaction #${data.transaction_id}`,
timer: 2000,
showConfirmButton: false,
}).then(() => {
// 🧾 Print QR code receipt
fetch(
`../../app/includes/POS/printKioskQR.php?id=${data.transaction_id}`
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
Swal.fire({
icon: "error",
title: "Server Error",
text: err.message,
});
});
}