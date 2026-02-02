<?php

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Validator.php';
require_once __DIR__ . '/../app/models/ContactMessage.php';

$auth = new Auth();
$auth->start();

$errors = [];
$old = $_POST ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Validator();
    $errors = $validator->validate($_POST, [
        'name' => 'required|min_length:2',
        'email' => 'required|email',
        'message' => 'required|min_length:5',
    ]);

    if (empty($errors)) {
        $model = new ContactMessage();
        $model->create([
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'subject' => trim($_POST['subject'] ?? '') ?: null,
            'message' => trim($_POST['message'] ?? ''),
        ], $auth->id());

        header('Location: contact.php?success=1');
        exit;
    }
    $old = $_POST;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact – Gamebits</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <a class="nav-brand" href="index.php">Gamebits</a>
                <ul class="nav-menu">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Mini-Games</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <?php if ($auth->isAdmin()): ?><li class="nav-item"><a class="nav-link" href="/admin/">Admin Dashboard</a></li><?php endif; ?>
                    <li class="nav-item"><?php if (!$auth->check()): ?><a class="nav-link" href="/login.php">Log In</a><?php else: ?><a class="nav-link" href="/logout.php">Logout</a><?php endif; ?></li>
                </ul>
                <form class="nav-search">
                    <input type="search" placeholder="Search games…" aria-label="Search">
                </form>
            </div>
        </nav>
    </header>
    <main>
        <h1>Contact Us</h1>
        <?php if (isset($_GET['success'])): ?>
            <p class="alert alert-success">Your message has been sent. Thank you!</p>
        <?php endif; ?>
        <?php foreach ($errors as $field => $msg): ?>
            <p class="alert alert-error"><?= htmlspecialchars($msg) ?></p>
        <?php endforeach; ?>
        <form method="POST">
            <div>
                <label>Name *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
            </div>
            <div>
                <label>Email *</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </div>
            <div>
                <label>Subject</label>
                <input type="text" name="subject" value="<?= htmlspecialchars($old['subject'] ?? '') ?>">
            </div>
            <div>
                <label>Message *</label>
                <textarea name="message" rows="6"><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
            </div>
            <button type="submit">Send</button>
        </form>
    </main>
    <footer class="footer">
        <p>&copy; 2024 Gamebits. Learn leadership by doing.</p>
    </footer>
</body>
</html>
