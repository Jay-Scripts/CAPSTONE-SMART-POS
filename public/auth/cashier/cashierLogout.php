<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager Logging Out</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
      <p class="text-lg font-medium text-[#3a2a1f]">Logging out</p>
      <p class="text-sm text-[#a07a60] mt-1" id="subtext">Ending your session...</p>
    </div>

    <!-- Dots -->
    <div class="flex gap-1.5 items-center">
      <span class="w-1.5 h-1.5 rounded-full bg-[#d97d54]" style="animation: pulse 1.2s ease-in-out infinite;"></span>
      <span class="w-1.5 h-1.5 rounded-full bg-[#d97d54]" style="animation: pulse 1.2s ease-in-out 0.2s infinite;"></span>
      <span class="w-1.5 h-1.5 rounded-full bg-[#d97d54]" style="animation: pulse 1.2s ease-in-out 0.4s infinite;"></span>
    </div>
  </div>

  <p class="text-xs text-[#c4a898] tracking-widest uppercase">Big Brew POS</p>

  <script>
    const msgs = ["Ending your session...", "Clearing your data...", "See you next time!"];
    let i = 0;
    const sub = document.getElementById("subtext");
    const iv = setInterval(() => {
      i++;
      if (i < msgs.length) sub.textContent = msgs[i];
      else clearInterval(iv);
    }, 900);

    setTimeout(() => {
      window.location.href = "./cashierLogin.php";
    }, 3000);
  </script>
</body>

</html>