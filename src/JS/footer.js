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
//Date and time
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

// ===============================================
// =     Network Checker Scripts - Stars Here    =
// ===============================================
 const onlineEl = document.getElementById('statusOnline');
const offlineEl = document.getElementById('statusOffline');

    function updateStatus() {
      if (navigator.onLine) {
        onlineEl.classList.remove('hidden');
        offlineEl.classList.add('hidden');
      } else {
        onlineEl.classList.add('hidden');
        offlineEl.classList.remove('hidden');
      }
    }

    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    updateStatus();

    
// ===============================================
// =      Network Checker Scripts - Ends Here    =
// ===============================================

// Set up event listeners for Onllne and Offline functions
window.addEventListener("online", updateNetworkStatus);
window.addEventListener("offline", updateNetworkStatus);

// Run on load
updateNetworkStatus();
updateDateTime();
setInterval(updateDateTime, 1000);