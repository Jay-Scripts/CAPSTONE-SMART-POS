<?php
if ($_SERVER)
?>


<!DOCTYPE html>
<html lang="en" class="da">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SMART POS LOGIN</title>
    <link rel="stylesheet" href="./public/css/input.css">
    <link rel="stylesheet" href="./public/css/output.css">
</head>

<body class="bg-[var(--background-color)] dark:">

    <div class="flex items-center justify-center h-screen" id="formContainer">
        <form
            action="index.php"
            method="POST"
            class="w-full max-w-sm bg-orange-500 border-4 border-black rounded-2xl hover:border-white transition-all p-8">
            <div
                class="flex flex-col items-center space-y-4 font-semibold text-gray-500">
                <img
                    src="./public/assets/SVG/LOGO/WLOGO.svg"
                    alt="BIG BREW LOGO LIGHT"
                    class="w-32 h-32" />

                <h1 class="text-[var(--text-color)] text-2xl">Scan your ID</h1>

                <input
                    class="w-full p-2 bg-white rounded-md border border-gray-700 focus:border-blue-700 transition"
                    placeholder="ID Number"
                    name="IDNumber"
                    maxlength="6"
                    pattern="\d{6}"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    required />
                <input
                    class="w-full p-2 bg-gray-50 rounded-full font-bold text-gray-900 border-[4px] border-gray-700 hover:border-blue-500 transition-all duration-200"
                    type="submit"
                    id="" />




            </div>
        </form>
    </div>
    <script src="/public/JS/theme-toggle.js"></script>
</body>

</html>