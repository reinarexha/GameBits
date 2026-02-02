<?php


require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/core/Validator.php';

$auth = new Auth();
$auth->start();


if ($auth->check()) {
    header('Location: /index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $errors = $validator->validate($_POST, [
        'username' => 'required',
        'password' => 'required',
    ]);

    if (empty($errors)) {
        $login = trim((string) $_POST['username']);
        $password = (string) $_POST['password'];
        $db = new Database();
        $pdo = $db->getConnection();

        
        $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $auth->login([
                'id'       => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role'],
            ]);
            if ($auth->isAdmin()) {
                header('Location: /admin/games/');
            } else {
                header('Location: /index.php');
            }
            exit;
        }

        $errors['login'] = 'Invalid username or password.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In – Gamebits</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main>
        <div class="login-wrapper">
            <div class="login-card">
                <h1>Log In</h1>
                <?php foreach ($errors as $field => $msg): ?>
                    <p class="error"><?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
                <form method="post">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Username or email" required
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn-primary">Log In</button>
                </form>
                <p class="signup-text">Don’t have an account? <a href="/register.php">Register</a></p>
            </div>
        </div>
    </main>
</body>
</html>
