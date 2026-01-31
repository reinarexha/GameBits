<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/ContactMessage.php';
require_once __DIR__ . '/../app/controllers/ContactController.php';

$controller = new ContactController();
$controller->handleForm();

$showSuccess = isset($_GET['success']);
$showError = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
</html>
