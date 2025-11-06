<?php
session_start();
include "../../../app/config/dbConnection.php";

$customerEmail = $password = $loginMessage = $popupAlert = "";

if (isset($_POST['CustomerLogin'])) {
  $customerEmail = trim($_POST['email']);
  $password = trim($_POST['password']);
  $sanitizedEmail = htmlspecialchars($customerEmail);

  // Validation
  if (empty($sanitizedEmail) || empty($password)) {
    $loginMessage = "<p class='text-red-500 text-sm'>Email and password are required.</p>";
  } elseif (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
    $loginMessage = "<p class='text-red-500 text-sm'>Invalid email format.</p>";
  } else {
    // Fetch account
    $stmt = $conn->prepare("
            SELECT ca.cust_account_id, ca.password, ca.status, ci.first_name, ci.last_name
            FROM customer_account ca
            JOIN customer_info ci ON ca.customer_id = ci.customer_id
            WHERE ci.email = :email
        ");
    $stmt->execute([':email' => $sanitizedEmail]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    // Plain text password check
    if ($account && $account['status'] === 'ACTIVE' && $account['password'] === $password) {
      // Set session
      $_SESSION['customer_id'] = $account['cust_account_id'];
      $_SESSION['customer_name'] = $account['first_name'] . ' ' . $account['last_name'];

      // Success popup
      $popupAlert = "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
                <script>
                    setTimeout(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Successful!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = './customerLoginSuccess.php';
                        });
                    }, 100);
                </script>";
    } else {
      $loginMessage = "<p class='text-red-500 text-sm'><b>Invalid email or password!</b></p>";
      $popupAlert = "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed!',
                        text: 'Invalid email or password.',
                    });
                </script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">

  <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
    <h1 class="text-2xl font-bold mb-6 text-center">Customer Login</h1>

    <!-- PHP login message -->
    <?= $loginMessage ?? '' ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($customerEmail) ?>"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          required>
      </div>

      <button type="submit" name="CustomerLogin"
        class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
        Login
      </button>
    </form>
  </div>

  <!-- Popup alerts from PHP -->
  <?= $popupAlert ?? '' ?>

</body>

</html>