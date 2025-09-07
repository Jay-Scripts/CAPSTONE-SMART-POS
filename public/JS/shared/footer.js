//=========================================
// NETWORK CONFIG STARTS HERE
//==========================================
const onlineStatus = document.querySelector(".onlineContainer");
const offlineStatus = document.querySelector(".offlineContainer");
const date = document.getElementById("footerDate");
const time = document.getElementById("footerTime");

function updateNetworkStatus() {
  if (navigator.onLine) {
    onlineStatus.classList.remove("hidden");
    onlineStatus.classList.add("flex", "opacity-100");

    offlineStatus.classList.remove("flex");
    offlineStatus.classList.add("hidden");
  } else {
    onlineStatus.classList.remove("flex");
    onlineStatus.classList.add("hidden");

    offlineStatus.classList.remove("hidden");
    offlineStatus.classList.add("flex", "opacity-100");
  }
}
// Set up event listeners for Onllne and Offline functions
window.addEventListener("online", updateNetworkStatus);
window.addEventListener("offline", updateNetworkStatus);

//=========================================
// NETWORK CONFIG ENDS HERE
//==========================================

//=========================================
// DATE AND TIME / CALENDAR STARTS HERE
//=========================================
function updateDateTime() {
  const now = new Date();

  // Format date: e.g. July 31, 2025
  const dateOptions = { year: "numeric", month: "long", day: "numeric" };
  date.textContent = now.toLocaleDateString(undefined, dateOptions);

  // Format time: 11:45:30 PM
  const timeOptions = {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: true,
  };
  time.textContent = now.toLocaleTimeString(undefined, timeOptions);
}

// Run on load
updateNetworkStatus();
updateDateTime();
setInterval(updateDateTime, 1000);
//=========================================
// DATE AND TIME / CALLENDAR ENDS HERE
//=========================================
