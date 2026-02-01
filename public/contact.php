<?php

require_once __DIR__ . '/../app/bootstrap.php';
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
<<<<<<< HEAD
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
                    <li class="nav-item"><?php if (!$auth->check()): ?><a class="nav-link" href="/login.php">Log In</a><?php else: ?><a class="nav-link" href="/auth/logout.php">Logout</a><?php endif; ?></li>
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
=======
    <title>Contact Us</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .contact-page { max-width: 400px; margin: 2rem auto; padding: 1rem; }
        .contact-page h1 { margin-bottom: 1rem; color: #fff; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.25rem; color: #fff; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box; }
        .form-group textarea { min-height: 100px; }
        .msg-success { color: #90EE90; margin-bottom: 1rem; padding: 0.5rem; background: #1a472a; border-radius: 8px; }
        .msg-error { color: #ff6b6b; margin-bottom: 1rem; padding: 0.5rem; background: #4a1a1a; border-radius: 8px; }
        .btn-submit { padding: 0.5rem 1.5rem; background: #8C00FF; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #5e54e9; }
    </style>
</head>
<body>
    <div class="contact-page">
        <h1>Contact Us</h1>

        <?php if ($showSuccess): ?>
            <p class="msg-success">Thank you! Your message has been sent.</p>
        <?php endif; ?>

        <?php if ($showError): ?>
            <p class="msg-error">Please fill in all fields.</p>
        <?php endif; ?>

        <form id="contactForm" method="post" action="contact.php">
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message *</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Send Message</button>
            </div>
        </form>
    </div>

    <script>
        // Simple validation: check required fields before submit
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var subject = document.getElementById('subject').value.trim();
            var message = document.getElementById('message').value.trim();

            if (name === '' || email === '' || subject === '' || message === '') {
                event.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
        });
    </script>
>>>>>>> ac19e3afa26e9b434db09b2547269241c3991a76
</body>
</html>
