
<?php
session_start();

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];

    if ($password === "12345") {
        $_SESSION["logged_in"] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $login_error = "Incorrect password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #3a1c71, #d76d77, #ffaf7b);
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background: #fff;
      color: #333;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 12px rgba(0,0,0,0.2);
      width: 300px;
      text-align: center;
    }
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      margin-top: 15px;
      background-color: #800020;
      color: white;
      border: none;
      padding: 10px 20px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
    }
    .error {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>🔒 Brandon Scene Admin Login</h2>
    <form method="POST">
      <input type="password" name="password" placeholder="Enter password" required />
      <button type="submit">Login</button>
    </form>
    <?php if ($login_error): ?>
      <div class="error"><?php echo $login_error; ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
