<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer View System — Staff View</title>
  <link href="../css/style.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="../assets/favcon/cvs.ico" type="image/x-icon" />
</head>

<body class="bg-[var(--background-color)] text-[var(--text-color)] font-sans min-h-screen flex flex-col">

  <!-- ── HEADER ── -->
  <header class="w-full flex justify-between items-center px-4 py-3 lg:px-6
                 bg-[var(--nav-bg)] border-b border-[var(--container-border)] shadow-sm z-50">
    <div class="flex items-center gap-3">
      <img src="../assets/SVG/LOGO/BLOGO.svg" class="h-10 theme-logo" alt="Logo" />
      <div>
        <h1 class="text-sm font-bold text-[var(--text-color)] leading-tight">Customer View System</h1>
        <p class="text-xs opacity-50 text-[var(--text-color)]">Staff View</p>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button id="theme-toggle" title="Toggle theme" aria-label="auto" aria-live="polite"
        class="w-9 h-9 flex items-center justify-center rounded-full border border-[var(--container-border)]
               hover:bg-[var(--container-border)] transition duration-200">
        <svg class="sun-and-moon text-[var(--text-color)]" aria-hidden="true" width="18" height="18" viewBox="0 0 24 24">
          <mask class="moon" id="moon-mask">
            <rect x="0" y="0" width="100%" height="100%" fill="white" />
            <circle cx="24" cy="10" r="6" fill="black" />
          </mask>
          <circle class="sun" cx="12" cy="12" r="6" mask="url(#moon-mask)" fill="currentColor" />
          <g class="sun-beams" stroke="currentColor">
            <line x1="12" y1="1" x2="12" y2="3" />
            <line x1="12" y1="21" x2="12" y2="23" />
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
            <line x1="1" y1="12" x2="3" y2="12" />
            <line x1="21" y1="12" x2="23" y2="12" />
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
          </g>
        </svg>
      </button>
    </div>
  </header>
  <!-- ── END HEADER ── -->

  <!-- ── MAIN ── -->
  <main class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 p-4 lg:p-6">

    <!-- COLUMN 1: SCANNER -->
    <section class="rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)]
                    shadow-sm p-6 flex flex-col gap-5">

      <!-- Section header -->
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-500/15 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
            <path d="M14 14h3v3M17 14v7M14 17h7" />
          </svg>
        </div>
        <div>
          <h2 class="text-sm font-bold text-[var(--text-color)] leading-tight">Scan Pick Slip</h2>
          <p class="text-xs opacity-50 text-[var(--text-color)]">Scan to mark order as <span class="text-green-500 font-semibold">Completed</span></p>
        </div>
      </div>

      <!-- Scanner input -->
      <div class="relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 opacity-30 text-[var(--text-color)]"
          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1" />
          <rect x="14" y="3" width="7" height="7" rx="1" />
          <rect x="3" y="14" width="7" height="7" rx="1" />
          <path d="M14 14h3v3M17 14v7M14 17h7" />
        </svg>
        <input
          id="qrInput"
          type="text"
          inputmode="numeric"
          pattern="[0-9]*"
          autocomplete="off"
          autofocus
          placeholder="Scan or enter QR code…"
          class="w-full pl-12 pr-4 py-4 text-base font-mono rounded-2xl
                 border-2 border-[var(--container-border)] bg-[var(--background-color)]
                 text-[var(--text-color)] placeholder-[var(--text-color)] placeholder-opacity-30
                 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20
                 transition-all duration-200" />
      </div>

      <!-- Hint -->
      <div class="flex items-start gap-2 px-4 py-3 rounded-xl border border-[var(--container-border)] bg-[var(--background-color)]">
        <svg class="w-4 h-4 shrink-0 mt-0.5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10" />
          <path d="M12 16v-4M12 8h.01" />
        </svg>
        <p class="text-xs opacity-60 text-[var(--text-color)] leading-relaxed">
          Connect your barcode scanner and scan the pick slip QR code. Status updates automatically on scan.
        </p>
      </div>

    </section>

    <!-- COLUMN 2: NOW SERVING -->
    <section class="rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)]
                    shadow-sm p-6 flex flex-col gap-4">

      <!-- Section header -->
      <div class="flex items-center gap-3 pb-3 border-b border-[var(--container-border)]">
        <div class="w-10 h-10 rounded-xl bg-green-500/15 flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
        </div>
        <div>
          <h2 class="text-sm font-bold text-[var(--text-color)] leading-tight">Now Serving</h2>
          <p class="text-xs opacity-50 text-[var(--text-color)]">Active orders ready for pickup</p>
        </div>
      </div>

      <!-- Order list -->
      <div id="nowServing"
        class="flex-1 overflow-y-auto space-y-2 max-h-[60vh] pr-1"
        style="scrollbar-width: thin; scrollbar-color: var(--container-border) transparent;">
        <p class="text-center text-sm opacity-40 py-8 text-[var(--text-color)] italic">Loading orders…</p>
      </div>

    </section>

  </main>
  <!-- ── END MAIN ── -->

  <!-- ── FOOTER ── -->
  <footer class="w-full px-4 py-3 border-t border-[var(--container-border)] bg-[var(--nav-bg)]">
    <div class="flex items-center justify-center gap-4 text-xs text-[var(--text-color)]">

      <span class="onlineContainer flex items-center gap-1.5 font-medium">
        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Online
      </span>
      <span class="offlineContainer hidden items-center gap-1.5 font-medium">
        <span class="w-2 h-2 rounded-full bg-red-500"></span> Offline
      </span>

      <span class="opacity-30">|</span>

      <span class="flex items-center gap-1.5 opacity-60">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-3.5 h-3.5" fill="currentColor">
          <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
        </svg>
        <span id="footerDate" class="font-medium">Loading…</span>
      </span>

      <span class="flex items-center gap-1.5 opacity-60">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-3.5 h-3.5" fill="currentColor">
          <path d="M582-298 440-440v-200h80v167l118 118-56 57ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
        </svg>
        <span id="footerTime" class="font-medium">Loading…</span>
      </span>

    </div>
  </footer>
  <!-- ── END FOOTER ── -->

  <!-- ── SCRIPTS ── -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <script src="../JS/shared/checkDBCon.js"></script>
  <script src="../JS/shared/footer.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <!-- Complete on card button click -->
  <script>
    document.addEventListener("click", async (e) => {
      if (e.target.closest(".complete-btn")) {
        const btn = e.target.closest(".complete-btn");
        const regId = btn.dataset.id;

        const confirm = await Swal.fire({
          title: "Mark as Completed?",
          text: `Transaction #${regId}`,
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#16a34a",
          cancelButtonColor: "#6b7280",
          confirmButtonText: "Yes, Complete it",
        });

        if (!confirm.isConfirmed) return;

        const res = await fetch("../../app/includes/CVS/CVSStaffViewCompleteTransaction.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: `regId=${regId}`,
        });
        const data = await res.json();

        Swal.fire({
          icon: data.status === "success" ? "success" : "info",
          title: data.status === "success" ? "Completed!" : "Info",
          text: data.message,
          timer: 1500,
          showConfirmButton: false
        });
      }
    });
  </script>

  <!-- QR Scanner input -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const qrInput = document.getElementById("qrInput");

      document.addEventListener("click", () => qrInput.focus());
      qrInput.focus();

      qrInput.addEventListener("input", (e) => {
        e.target.value = e.target.value.replace(/\D/g, "");
      });

      qrInput.addEventListener("keypress", async (e) => {
        if (e.key !== "Enter") return;
        e.preventDefault();

        const regId = qrInput.value.trim();
        if (!regId) {
          Swal.fire({
            icon: "warning",
            title: "Invalid QR",
            text: "Please scan a valid numeric QR code.",
            timer: 1500,
            showConfirmButton: false
          });
          qrInput.value = "";
          return;
        }

        try {
          const res = await fetch("../../app/includes/CVS/CVSStaffViewCompleteTransaction.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `regId=${encodeURIComponent(regId)}`
          });
          const data = await res.json();

          Swal.fire({
            icon: data.status === "success" ? "success" : data.status === "info" ? "info" : "error",
            title: data.status === "success" ? "Completed!" : data.status === "info" ? "Info" : "Error",
            text: data.message,
            timer: 1600,
            showConfirmButton: false
          });
        } catch {
          Swal.fire({
            icon: "error",
            title: "Network Error",
            text: "Unable to reach the server.",
            timer: 1600,
            showConfirmButton: false
          });
        }

        qrInput.value = "";
        qrInput.focus();
      });
    });
  </script>

  <!-- Now Serving loader -->
  <script>
    async function loadNowServing() {
      try {
        const res = await fetch("../../app/includes/CVS/CVSfetchOrdersStaffView.php");
        const data = await res.json();
        const container = document.getElementById("nowServing");
        if (!container) return;

        if (data.length === 0) {
          container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-12 gap-2 opacity-40">
              <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M8 15s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>
              </svg>
              <p class="text-sm text-[var(--text-color)]">No orders serving now</p>
            </div>`;
          return;
        }

        container.innerHTML = data.map(id => `
          <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-2xl
                      border border-green-500/30 bg-green-500/10
                    ">

            <!-- Order number -->
            <div class="flex items-center gap-3">
              <div class="w-2 h-10 rounded-full bg-green-500 shrink-0"></div>
              <div>
                <p class="text-xs opacity-50 text-[var(--text-color)] uppercase tracking-wide font-semibold">Order</p>
                <p class="text-2xl font-bold text-[var(--text-color)]">#${id}</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
              <!-- Speak -->
              <button onclick="speakOrder(${id})"
                class="w-9 h-9 flex items-center justify-center rounded-xl
                       border border-[var(--container-border)] bg-[var(--background-color)]
                       text-[var(--text-color)] hover:bg-blue-500 hover:text-white hover:border-blue-500
                       active:scale-90 transition-all duration-150"
                title="Announce order">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 10v4h4l5 5V5L7 10H3zm13.5 2c0-1.77-1-3.29-2.5-4.03v8.06c1.5-.74 2.5-2.26 2.5-4.03zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                </svg>
              </button>
           
            </div>

          </div>
        `).join("");

      } catch (err) {
        console.error("Error loading now serving:", err);
      }
    }

    loadNowServing();
    setInterval(loadNowServing, 2000);
  </script>

  <!-- Speak order -->
  <script>
    function speakOrder(orderNumber) {
      const msg = new SpeechSynthesisUtterance(`Now serving customer number ${orderNumber}`);
      msg.lang = "en-US";
      msg.rate = 0.9;
      msg.pitch = 1;
      const voices = speechSynthesis.getVoices();
      if (voices.length > 0) msg.voice = voices.find(v => v.lang === "en-US") || voices[0];
      speechSynthesis.speak(msg);
    }
  </script>

  <style>
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(4px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>

</body>

</html>