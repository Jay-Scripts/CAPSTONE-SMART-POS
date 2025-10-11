  <?php
  include "../../../app/config/dbConnection.php";
  include "../../../app/includes/BVS/BVSLoginContrl.php";
  ?>
  <!doctype html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BVS Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


    <style>
      /* Milk tea fill animation for full screen */
      @keyframes fillScreen {
        0% {
          height: 0%;
        }

        100% {
          height: 100%;
        }
      }

      /* Boba bouncing animation */
      @keyframes bounce {

        0%,
        100% {
          transform: translateY(0);
        }

        50% {
          transform: translateY(-8px);
        }
      }

      /* Floating bubble animation */
      @keyframes floatUp {
        0% {
          transform: translateY(0);
          opacity: 0;
        }

        50% {
          opacity: 0.6;
        }

        100% {
          transform: translateY(-60px);
          opacity: 0;
        }
      }

      /* Fullscreen milk tea background */
      .milk-tea-fill {
        animation: fillScreen 5s ease-out forwards infinite alternate;
        transform-origin: bottom;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 0%;
        background: linear-gradient(180deg, #f3d6b3 0%, #dba77f 100%);
        z-index: -1;
      }

      /* Boba style */
      .boba {
        animation: bounce 1.5s infinite ease-in-out;
        width: 12px;
        height: 12px;
        background: #6b3e26;
        border-radius: 50%;
        position: absolute;
      }

      /* Floating bubble style */
      .bubble {
        width: 6px;
        height: 6px;
        background: rgba(107, 62, 38, 0.8);
        border-radius: 50%;
        position: absolute;
        animation: floatUp 3s infinite ease-in-out;
      }
    </style>
  </head>

  <body class="relative min-h-screen overflow-hidden">
    <!-- Milk Tea Fill -->
    <div class="milk-tea-fill"></div>

    <!-- Boba Pearls -->
    <div
      class="boba"
      style="bottom: 20px; left: 25%; animation-delay: 0s"></div>
    <div
      class="boba"
      style="bottom: 50px; left: 50%; animation-delay: 0.3s"></div>
    <div
      class="boba"
      style="bottom: 80px; left: 70%; animation-delay: 0.6s"></div>
    <div
      class="boba"
      style="bottom: 120px; left: 40%; animation-delay: 0.9s"></div>

    <!-- Floating bubbles -->
    <div
      class="bubble"
      style="left: 10%; bottom: 10px; animation-delay: 0s"></div>
    <div
      class="bubble"
      style="left: 30%; bottom: 30px; animation-delay: 1s"></div>
    <div
      class="bubble"
      style="left: 60%; bottom: 20px; animation-delay: 0.5s"></div>
    <div
      class="bubble"
      style="left: 80%; bottom: 40px; animation-delay: 1.2s"></div>

    <!-- Login Card -->
    <div
      id="loginModal"
      class="fixed inset-0 flex items-center justify-center z-50 p-4">
      <div
        class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 lg:p-10 w-full max-w-md animate-fade-in">
        <!-- Logo -->
        <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
          <img
            src="../../assets/SVG/LOGO/BLOGO.svg"
            alt="LOGO"
            class="h-20 w-20 theme-logo" />
        </div>

        <!-- Title -->
        <h2
          class="text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
          Smart POS
        </h2>

        <!-- Subtitle -->
        <p
          class="text-[var(--text-color)] text-sm lg:text-base font-medium text-center mb-6">
          Barista View System
        </p>

        <!-- Form -->
        <form method="POST" class="space-y-6">
          <!-- Input -->
          <div class="relative group">
            <input
              class="w-full px-4 py-3 text-sm sm:text-base border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 hover:border-gray-300 placeholder:text-gray-400 font-medium outline-none focus:-translate-y-0.5"
              placeholder="ID Number"
              name="IDNumber"
              type="password"
              maxlength="6"
              oninput="this.value = this.value.replace(/[^0-9]/g, '')"
              required
              autofocus />

            <div
              class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
              <svg
                class="size-5 text-[var(--text-color)] group-focus-within:text-blue-500 transition-colors duration-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </div>
          <p class="mt-2">
            <?php echo $BVSModuleLoginMessage; ?>
          </p>
          <!-- Button -->
          <button
            type="submit"
            name="BVSModuleLogin"
            class="w-full bg-gradient-to-r from-stone-700 via-zinc-800 to-stone-900 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-stone-800 hover:via-zinc-900 hover:to-black focus:ring-4 focus:ring-stone-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none active:translate-y-0 relative overflow-hidden group text-sm sm:text-base lg:text-lg">
            <div
              class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
            Login
          </button>
        </form>
        <?php
        echo $BVSModulePopupAlert;
        ?>
      </div>
    </div>

  </body>

  </html>