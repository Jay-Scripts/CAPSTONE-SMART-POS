can i turn this same flow but without payment? the just print a small piece of printed QR version of transaction number so that fetchable via POS
and update ordered by kiosk status to pending
tbl for ref



CREATE TABLE REG_TRANSACTION (
REG_TRANSACTION_ID INT AUTO_INCREMENT PRIMARY KEY,
cust_account_id INT NULL, --
STAFF_ID INT NOT NULL,
ORDERED_BY ENUM('KIOSK', 'POS', 'REWARDS APP') DEFAULT 'POS',
TOTAL_AMOUNT DECIMAL(6,2) DEFAULT 0.00,
VAT_AMOUNT DECIMAL(6,2) NOT NULL DEFAULT 0.00,
STATUS ENUM('PENDING', 'PAID', 'NOW SERVING', 'COMPLETED', 'REFUNDED', 'WASTE', 'VOID') DEFAULT 'PENDING',
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (cust_account_id) REFERENCES CUSTOMER_ACCOUNT(cust_account_id) ON DELETE SET NULL,
FOREIGN KEY (STAFF_ID) REFERENCES STAFF_INFO(STAFF_ID) ON DELETE CASCADE
);

heres the js


function kioskCheckout() {
if (cart.length === 0) {
Swal.fire({
icon: "warning",
title: "Empty Cart",
text: "Add items before finalizing payment.",
});
return;
}


const formData = new FormData();
formData.append("order_data", JSON.stringify(cart));
formData.append("payment_type", paymentType);
formData.append("amount_sent", tendered);
formData.append("change_amount", change);
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
title: "Payment Successful!",
text: Change: ₱${change.toFixed(2)},
timer: 1500,
showConfirmButton: false,
}).then(() => {
// 🧾 Fetch and print receipt
fetch(
../../app/includes/POS/printKioskReceipt.php?id=${data.transaction_id}
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
text: data.message || "Payment failed.",
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