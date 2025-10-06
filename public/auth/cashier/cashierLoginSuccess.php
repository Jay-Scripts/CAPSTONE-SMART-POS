<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cashier Logging in</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Milk tea cup fill animation */
    @keyframes fillCup {
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

    /* Cup fill */
    .milk-tea-fill {
      animation: fillCup 3s ease-out forwards;
      transform-origin: bottom;
      background: linear-gradient(180deg, #f3d6b3 0%, #dba77f 100%);
      width: 100%;
      height: 0%;
      border-radius: 0 0 1rem 1rem;
      position: absolute;
      bottom: 0;
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

    /* Floating bubbles */
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

<body
  class="relative min-h-screen flex items-center justify-center bg-gradient-to-b from-[#fdebd0] to-[#f8e8d0] overflow-hidden">
  <!-- Milk Tea Cup -->
  <div class="relative w-44 h-60 sm:w-60 sm:h-80">
    <div
      class="absolute inset-0 border-4 border-[#d97d54] rounded-3xl overflow-hidden shadow-lg">
      <div class="milk-tea-fill"></div>
    </div>

    <!-- Boba Pearls -->
    <div class="boba" style="bottom: 4rem; left: 1.5rem"></div>
    <div
      class="boba"
      style="bottom: 5rem; left: 3rem; animation-delay: 0.3s"></div>
    <div
      class="boba"
      style="bottom: 6rem; left: 2rem; animation-delay: 0.6s"></div>
    <div
      class="boba"
      style="bottom: 7rem; left: 2.5rem; animation-delay: 0.9s"></div>

    <!-- Floating bubbles -->
    <div
      class="bubble"
      style="left: 20px; bottom: 20px; animation-delay: 0s"></div>
    <div
      class="bubble"
      style="left: 40px; bottom: 15px; animation-delay: 1s"></div>
    <div
      class="bubble"
      style="left: 28px; bottom: 10px; animation-delay: 0.5s"></div>
  </div>

  <!-- SMART POS Text -->
  <h1
    class="absolute bottom-10 text-2xl sm:text-3xl font-bold text-[#d97d54]">
    Logging In...
  </h1>

  <script>
    // Simulate loading and redirect to login after 3 seconds
    setTimeout(() => {
      window.location.href = "../../modules/POS.php";
    }, 2500);
  </script>
</body>

</html>