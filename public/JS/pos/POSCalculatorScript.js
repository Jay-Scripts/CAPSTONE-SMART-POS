let originalTotal = 0;
let total = originalTotal;
let tendered = 0;
let buffer = "";
let transType = null;

function openCalculator() {
  document.getElementById("calculatorModal").classList.remove("hidden");
  updateDisplay();
}
function closeCalculator() {
  document.getElementById("calculatorModal").classList.add("hidden");
  tendered = 0;
  buffer = "";
  transType = null;
  total = originalTotal;
  updateDisplay();
}
function addCash(amount) {
  tendered += amount;
  updateDisplay();
}
function manualKey(num) {
  buffer += num;
  tendered = parseFloat(buffer);
  updateDisplay();
}
function clearCash() {
  tendered = 0;
  buffer = "";
  updateDisplay();
}
function finalizePayment() {
  alert(
    "Payment Accepted!\nType: " +
      (transType ?? "Regular") +
      "\nChange: ₱" +
      (tendered - total).toFixed(2)
  );
  closeCalculator();
}
function updateDisplay() {
  document.getElementById("totalAmount").innerText = "₱" + total.toFixed(2);
  document.getElementById("tenderedAmount").innerText =
    "₱" + tendered.toFixed(2);
  document.getElementById("changeAmount").innerText =
    "₱" + (tendered - total).toFixed(2);
}
function openQrPopup() {
  document.getElementById("qrPopup").classList.remove("hidden");
}
function openEPaymentPopup() {
  document.getElementById("EPaymentPopup").classList.remove("hidden");
}
function closeQrPopup() {
  document.getElementById("qrPopup").classList.add("hidden");
}
function closeEPaymentPopup() {
  document.getElementById("EPaymentPopup").classList.add("hidden");
}
function applyDiscount(type) {
  if (transType) {
    alert("Discount already applied: " + transType);
    return;
  }
  total = originalTotal * 0.8;
  transType = type;
  updateDisplay();
}
