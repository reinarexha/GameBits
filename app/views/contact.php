<?php
/**
 * Contact form view - shows the form and any success/error messages.
 * Variables from controller: $success, $errors, $name, $email, $subject, $message
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .contact-wrapper { padding: 2rem; max-width: 500px; margin: 0 auto; text-align: center; }
        .contact-wrapper h1 { margin-bottom: 1rem; color: #fff; }
        .success-message { color: #90EE90; margin-bottom: 1rem; }
        .error-list { color: #ff6b6b; list-style: none; margin-bottom: 1rem; text-align: left; }
        textarea.form-input { height: auto; min-height: 120px; padding: 12px 18px; }
        .btn-submit { width: 315px; height: 61px; border: none; border-radius: 25px; background: #8C00FF; color: #fff; font-size: 16px; cursor: pointer; }
        .btn-submit:hover { background: #5e54e9; }
    </style>
</head>
<body>
    <div class="contact-wrapper">
        <h1>Contact Us</h1>

        <?php if ($success): ?>
            <p class="success-message">Thank you! Your message has been sent.</p>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <ul class="error-list">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" class="form-input" value="<?php echo htmlspecialchars($subject); ?>" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-input" rows="5" required><?php echo htmlspecialchars($message); ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-submit">Send Message</button>
            </div>
        </form>
    </div>
</body>
</html>
