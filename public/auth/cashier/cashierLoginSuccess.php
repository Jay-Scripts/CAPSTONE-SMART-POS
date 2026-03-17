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

<body class="min-h-screen flex flex-col items-center justify-center gap-8 bg-[#fdf8f4]">

  <div class="flex flex-col items-center gap-5">
    <!-- Spinner -->
    <div class="relative w-12 h-12">
      <svg viewBox="0 0 48 48" width="48" height="48" fill="none">
        <circle cx="24" cy="24" r="20" stroke="#e8d5c4" stroke-width="3" />
        <circle cx="24" cy="24" r="20" stroke="#d97d54" stroke-width="3"
          stroke-linecap="round" stroke-dasharray="30 96"
          style="transform-origin:center; animation: spin 1s linear infinite;" />
      </svg>
    </div>

    <!-- Text -->
    <div class="text-center">
      <p class="text-lg font-medium text-[#3a2a1f]">Logging in</p>
      <p class="text-sm text-[#a07a60] mt-1" id="subtext">Verifying your credentials...</p>
    </div>

    <!-- Dots -->
    <div class="flex gap-1.5 items-center">
      <span class="dot w-1.5 h-1.5 rounded-full bg-[#d97d54]" style="animation: pulse 1.2s ease-in-out infinite;"></span>
      <span class="dot w-1.5 h-1.5 rounded-full bg-[#d97d54]" style="animation: pulse 1.2s ease-in-out 0.2s infinite;"></span>
      <span class="dot w-1.5 h-1.5 rounded-full bg-[#d97d54]" style="animation: pulse 1.2s ease-in-out 0.4s infinite;"></span>
    </div>
  </div>

  <p class="text-xs text-[#c4a898] tracking-widest uppercase">Big Brew POS</p>

  <style>
    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 0.3;
        transform: scale(0.85);
      }

      50% {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>

  <script>
    const msgs = ["Verifying your credentials...", "Almost there...", "Redirecting you now..."];
    let i = 0;
    const sub = document.getElementById("subtext");
    const iv = setInterval(() => {
      i++;
      if (i < msgs.length) sub.textContent = msgs[i];
      else clearInterval(iv);
    }, 900);

    setTimeout(() => {
      window.location.href = "../../modules/POS.php";
    }, 2500);
  </script>
</body>

</html>