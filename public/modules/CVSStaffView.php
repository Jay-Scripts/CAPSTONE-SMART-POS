<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer View System Staff View</title>
  <!--  linked css below for animations purpose -->
  <link href="../css/style.css" rel="stylesheet" />

  <!--  linked css below for tailwind dependencies to work ofline -->
  <!-- <link href="../css/output.css" rel="stylesheet" /> -->
  <!--  linked script below cdn of tailwind for online use -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="shortcut icon"
    href="../assets/favcon/cvs.ico"
    type="image/x-icon" />
</head>

<body class="bg-[var(--background-color)] text-white font-sans min-h-screen">

  <header
    class="w-full flex justify-between items-center gap-4 px-3 py-2 lg:px-6 lg:py-3 md:static sm:px-4 sm:py-2 bg-[var(--nav-bg)] text-[var(--nav-text)] border-b shadow-md z-50">
    <h1
      class="text-2xl flex-1 lg:text-left lg:flex-none sm:text-lg md:text-xl font-semibold text-[var(--text-color)]">
      <span class="flex items-center">
        <img
          src="../assets/SVG/LOGO/BLOGO.svg"
          class="h-[3rem] theme-logo m-1"
          alt="Module Logo" />
        Customer View System Staff View
      </span>
    </h1>

    <!-- 
      ==================================
      =   Theme toggle Btn Starts Here =
      ==================================
    -->
    <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
      <button
        class="p-2 sm:p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200"
        id="theme-toggle"
        title="Toggle theme"
        aria-label="auto"
        aria-live="polite">
        <svg
          class="sun-and-moon text-gray-600 dark:text-gray-200"
          aria-hidden="true"
          width="24"
          height="24"
          viewBox="0 0 24 24">
          <mask class="moon" id="moon-mask">
            <rect x="0" y="0" width="100%" height="100%" fill="white" />
            <circle cx="24" cy="10" r="6" fill="black" />
          </mask>
          <circle
            class="sun"
            cx="12"
            cy="12"
            r="6"
            mask="url(#moon-mask)"
            fill="currentColor" />
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

      <!-- 
      ================================
      =   Theme toggle Btn Ends Here =
      ================================
    -->


    </div>

    </div>
  </header>
  <!-- 
      =============================
      = Main Contents Starts Here =
      =============================
    -->

  <main class="flex flex-col items-center justify-center min-h-[calc(100vh-150px)] px-4 py-8 sm:px-6 lg:px-10">

    <!-- Scanner Card -->
    <section
      class="w-full max-w-sm sm:max-w-md lg:max-w-xl bg-white/10 backdrop-blur-md border border-gray-400/30 rounded-2xl shadow-lg p-6 sm:p-8 text-center transition hover:scale-[1.01] duration-200">

      <h2 class="text-xl sm:text-2xl font-semibold text-[var(--text-color)] mb-5">
        Scan Pick Slip to Mark as <span class="text-green-400">Completed</span>
      </h2>

      <input
        id="qrInput"
        type="text"
        inputmode="numeric"
        pattern="[0-9]*"
        autocomplete="off"
        autofocus
        class="w-full text-center text-lg p-3 border-2 text-[var(--text-color)] placeholder-[var(--text-color)] rounded-md focus:ring-2 bg-[var(--background-color)] focus:ring-blue-400"
        placeholder="Scan or enter QR code..." />

      <p class="text-xs sm:text-sm text-[var(--text-color)]  mt-3">
        Connect your scanner and scan the QR code. It will automatically update the status.
      </p>
    </section>
  </main>

  <script>
    document.addEventListener("click", async (e) => {
      if (e.target.classList.contains("complete-btn")) {
        const regId = e.target.dataset.id;

        const confirm = await Swal.fire({
          title: "Mark as Completed?",
          text: `Transaction #${regId}`,
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
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

        if (data.status === "success") {
          Swal.fire("Done!", data.message, "success");
          // Optional: refresh or remove the completed item from UI
        } else {
          Swal.fire("Info", data.message, "info");
        }
      }
    });
  </script>



  <!-- 
      ===========================
      = Main Contents Ends Here =
      ===========================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Starts Here                                                                  =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

  <footer class="fixed bottom-0 w-full bg-[transparent] px-3 p-5 z-50">
    <div class="flex items-center gap-1">
      <!-- Centered Info -->
      <div
        class="absolute left-1/2 -translate-x-1/2 flex flex-wrap justify-center items-center gap-3 text-[11px]">
        <!-- Online/Offline -->
        <span
          class="onlineContainer flex items-center gap-1 text-base font-medium text-[var(--text-color)]">
          <span class="text-[14px] text-green-600">●</span> Online
        </span>
        <span
          class="offlineContainer hidden items-center gap-1 text-base font-medium text-[var(--text-color)]">
          <span class="text-[14px] text-red-600">●</span> Offline
        </span>

        <!-- Date -->
        <span class="flex items-center gap-1 text-[var(--text-color)]">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 -960 960 960"
            class="h-[1vw]"
            fill="var(--text-color)">
            <path
              d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
          </svg>
          <span
            id="footerDate"
            class="font-medium text-base text-[var(--text-color)]">Loading...</span>
        </span>

        <!-- Time -->
        <span
          class="flex items-center text-base gap-1 text-[var(--text-color)]">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 -960 960 960"
            class="h-[1vw]"
            fill="var(--text-color)">
            <path
              d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
          </svg>
          <span
            id="footerTime"
            class="text-base font-medium text-[var(--text-color)]">Loading...</span>
        </span>
      </div>
    </div>
  </footer>

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Ends Here                                                                    =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->


  <!-- 
      ========================
      = JS Links Starts Here =
      ========================
    -->


  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for checking DB status -->
  <script src="../JS/shared/checkDBCon.js"></script>
  <!-- linked JS file below for footer scrpts -->
  <script src="../JS/shared/footer.js"></script>

  <!-- 
      ======================
      = JS Links Ends Here =
      ======================
    -->
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const qrInput = document.getElementById("qrInput");

      // Keep focus for scanner input
      const keepFocus = () => qrInput.focus();
      document.addEventListener("click", keepFocus);
      qrInput.focus();

      // ✅ Allow only numeric input in real time
      qrInput.addEventListener("input", (e) => {
        e.target.value = e.target.value.replace(/\D/g, ""); // remove non-digits
      });

      // ✅ Handle Enter / QR scan submission
      qrInput.addEventListener("keypress", async (e) => {
        if (e.key === "Enter") {
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
            qrInput.focus();
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
            const iconType =
              data.status === "success" ?
              "success" :
              data.status === "info" ?
              "info" :
              "error";

            Swal.fire({
              icon: iconType,
              title: data.status === "success" ?
                "Completed!" : data.status === "info" ?
                "Info" : "Error",
              text: data.message,
              timer: 1600,
              showConfirmButton: false
            });
          } catch (err) {
            Swal.fire({
              icon: "error",
              title: "Network Error",
              text: "Unable to reach the server. Please try again.",
              timer: 1600,
              showConfirmButton: false
            });
          }

          qrInput.value = "";
          qrInput.focus();
        }
      });
    });
  </script>




</body>

</html>