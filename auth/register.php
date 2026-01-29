<?php


require_once __DIR__ . '/../app/bootstrap.php';

$auth = new Auth();
$auth->start();

if ($auth->check()) {
    header('Location: /index.php');
    exit;
}

$errors = [];
$old = $_POST ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $validator->validate($_POST, [
        'username' => 'required|min_length:3',
        'email'    => 'required|email',
        'password' => 'required|min_length:6',
    ]);
    $errors = $validator->errors();

    if (!$validator->fails()) {
        $username = trim((string) $_POST['username']);
        $email    = trim((string) $_POST['email']);
        $password = (string) $_POST['password'];

        $db = new Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Username or email already in use.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, created_at, updated_at) VALUES (?, ?, ?, 'user', GETDATE(), GETDATE())");
            $stmt->execute([$username, $email, $hash]);
            header('Location: login.php');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – Gamebits</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main>
        <div class="signup-wrapper">
            <div class="signup-card">
                <h1>Register</h1>
                <?php foreach ($errors as $msg): ?>
                    <p class="error"><?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username" required
                               value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email" required
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn-primary">Register</button>
                </form>
                <p class="login-text">Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </div>
    </main>
</body>
</html>
