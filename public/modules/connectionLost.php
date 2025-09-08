<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Lost</title>
    <!--  linked css below for animations purpose -->
    <link href="../css/input.css" rel="stylesheet" />
    <!--  linked css below for tailwind dependencies to work ofline -->
    <link href="../css/output.css" rel="stylesheet" />
    <link
        rel="shortcut icon"
        href="../assets/favcon/logo.ico"
        type="image/x-icon" />
    <style>
        @keyframes slide-in-bck-center {
            0% {
                transform: translateZ(600px);
                opacity: 0;
            }

            100% {
                transform: translateZ(0);
                opacity: 1;
            }
        }

        body {
            animation: slide-in-bck-center 0.7s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-xl rounded-lg p-8 max-w-md w-full border border-red-200">
        <div class="flex flex-col items-center text-center">

            <h1 class="text-2xl font-bold text-red-600">Database Connection Lost</h1>
            <p class="text-gray-700 mt-4">We are unable to connect to the database server. Please check your database settings and try again.</p>
            <p class="text-gray-600 mt-2">If the problem persists, contact your administrator or support team.</p>
            <a href="index.php" class="mt-6 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Return to Main Page</a>
        </div>
    </div>

    <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Starts Here                                                                  =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

    <footer
        class="fixed bottom-0 w-full bg-[transparent] px-3 p-5 z-50">
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class=" h-[1vw] " fill="var(--text-color)">
                        <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
                    </svg>
                    <span
                        id="footerDate"
                        class="font-medium text-base text-[var(--text-color)]">Loading...</span>
                </span>

                <!-- Time -->
                <span
                    class="flex items-center text-base gap-1 text-[var(--text-color)]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class=" h-[1vw] " fill="var(--text-color)">
                        <path d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
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
    <script>
        const checker = setInterval(() => {
            fetch("../../app/config/dbConnection.php")
                .then(res => res.text())
                .then(data => {
                    if (data.includes("Connected")) {
                        clearInterval(checker);
                        history.back(); // go back to last page
                    }
                })
                .catch(() => {
                    // Still down, do nothing
                });
        }, 1000);
    </script>


</body>

</html>