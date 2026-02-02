<?php
// auth/login.php
session_start();

require_once __DIR__ . "/../config/db.php"; // <-- change if your db connection file is elsewhere

// If already logged in, redirect based on role
if (isset($_SESSION['user_id'])) {
  if (($_SESSION['role'] ?? '') === 'admin') {
    header("Location: /admin/dashboard.php");
    exit;
  }
  header("Location: /games.php");
  exit;
}

$error = "";
$emailValue = "";

// Handle login submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $emailValue = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

  if ($email === '' || $password === '') {
    $error = "Please fill in both email and password.";
  } else {
    try {
      // IMPORTANT: change column names if yours differ
      $stmt = $pdo->prepare("SELECT id, email, password_hash, role FROM users WHERE email = ? LIMIT 1");
      $stmt->execute([$email]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Generic error for both cases (prevents email enumeration)
      if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = "Invalid email or password.";
      } else {
        // Successful login
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role']; // 'admin' or 'user'

        // Redirect based on role
        if ($user['role'] === 'admin') {
          header("Location: /admin/dashboard.php");
          exit;
        }

        header("Location: /games.php");
        exit;
      }
    } catch (Exception $e) {
      // Don't expose raw DB errors in production
      $error = "Something went wrong. Please try again.";
    }
  }
}

// Optional: show messages passed via ?error=...
if (isset($_GET['error'])) {
  if ($_GET['error'] === 'unauthorized') $error = "You are not allowed to access that page.";
  if ($_GET['error'] === 'login_required') $error = "Please log in to continue.";
  if ($_GET['error'] === 'invalid') $error = "Invalid email or password.";
  if ($_GET['error'] === 'empty') $error = "Please fill in both email and password.";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: Arial, sans-serif; padding: 24px; }
    .card { max-width: 420px; margin: 0 auto; border: 1px solid #ddd; border-radius: 10px; padding: 20px; }
    label { display: block; margin-top: 12px; }
    input { width: 100%; padding: 10px; margin-top: 6px; }
    button { width: 100%; padding: 10px; margin-top: 16px; cursor: pointer; }
    .error { background: #ffe5e5; border: 1px solid #ffb3b3; padding: 10px; border-radius: 8px; margin-bottom: 12px; }
    .links { margin-top: 14px; text-align: center; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Login</h2>

    <?php if ($error !== ""): ?>
      <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form method="POST" action="/auth/login.php">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" required value="<?php echo $emailValue; ?>">

      <label for="password">Password</label>
      <input id="password" name="password" type="password" required>

      <button type="submit">Log in</button>
    </form>

    <div class="links">
      <p>No account? <a href="/auth/register.php">Register</a></p>
    </div>
  </div>
</body>
</html>
