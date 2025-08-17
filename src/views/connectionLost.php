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

    <script>
        const checker = setInterval(() => {
            fetch("../config/dbConnection.php")
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
        }, 500);
    </script>


</body>

</html>