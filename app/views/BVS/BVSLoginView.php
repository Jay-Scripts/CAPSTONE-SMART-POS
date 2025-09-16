<?php
include "../../app/controllers/BVS/BVSLoginContrl.php";
?>

<!-- 
========================================
=      Login Modal - Starts Here       =
======================================== -->
<div
    id="loginModal"
    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div
        class="glass-card rounded-2xl shadow-lg p-6 sm:p-8 lg:p-10 w-full max-w-md animate-fade-in">
        <!-- Logo -->
        <div class="w-20 h-20 mx-auto mb-4 flex items-center justify-center">
            <img
                src="../assets/SVG/LOGO/BLOGO.svg"
                alt="LOGO"
                class="h-20 w-20 theme-logo" />
        </div>

        <!-- Title -->
        <h2 class="text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
            Scan your ID
        </h2>

        <!-- Subtitle -->
        <p class="text-[var(--text-color)] text-sm lg:text-base font-medium text-center mb-6">
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
                    required />
                <p class="mt-2">
                    <?php
                    // Show error or success message
                    if (!empty($BVSLoginMessage)) {
                        echo $BVSLoginMessage;
                    }
                    ?>
                </p>
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                    <svg class="size-5 text-[var(--text-color)] group-focus-within:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>

            <!-- Button -->
            <button
                type="submit"
                name="BVSLogin"
                class="w-full bg-gradient-to-r from-stone-700 via-zinc-800 to-stone-900 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-stone-800 hover:via-zinc-900 hover:to-black focus:ring-4 focus:ring-stone-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none active:translate-y-0 relative overflow-hidden group text-sm sm:text-base lg:text-lg">
                <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                Login
            </button>
        </form>
    </div>
</div>

<!-- Auto-close modal if login success -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        if (params.get("login") === "success") {
            const modal = document.getElementById("loginModal");
            if (modal) modal.style.display = "none"; // hide modal
        }
    });
</script>
<header
    class="w-full flex justify-between items-center gap-4 px-3 py-2 lg:px-6 lg:py-3 md:static sm:px-4 sm:py-2 bg-[var(--nav-bg)] text-[var(--nav-text)] border-b shadow-md z-50">
    <h1
        class="text-2xl flex-1 lg:text-left lg:flex-none sm:text-lg md:text-xl font-semibold text-[var(--text-color)]">
        <span class="flex items-center">
            <img
                src="../assets/SVG/LOGO/BLOGO.svg"
                class="h-[3rem] theme-logo m-1"
                alt="Module Logo" />
            Barista View System
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
        <!-- Profile Dropdown Example -->
        <div class="flex justify-end p-4 text-[var(--text-color)]">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" role="img" aria-label="Cashier" class="inline-block">
                    <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="6" r="2.5" />
                        <path d="M9.5 11h5c1.5 0 2.5 1 2.5 2.5V15H7v-1.5c0-1.5 1-2.5 2.5-2.5z" />
                        <rect x="3" y="15" width="18" height="5" rx="1" />
                        <rect x="16" y="12" width="4" height="2" rx="0.5" />
                    </g>
                </svg>
            </span>

            <select onchange="if(this.value === 'logout'){window.location.href='?logout=1'}">
                <option selected disabled>
                    <?php echo isset($_SESSION['staff_name']) ? $_SESSION['staff_name'] : "No Staff"; ?>
                </option>
                <option value="logout" class="text-red-600">Logout</option>
            </select>

        </div>

    </div>
</header>