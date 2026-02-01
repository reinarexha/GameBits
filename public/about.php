<?php

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/models/PageContent.php';

$auth = new Auth();
$auth->start();

$pageContent = new PageContent();

$aboutText = $pageContent->getText('about', 'about_text', '');

$rows = $pageContent->getAllByPage('about');

$sectionRows = array_filter(
    $rows,
    fn($r) => strpos($r['section_key'] ?? '', 'section_') === 0
);

$displayRows = !empty($sectionRows) ? $sectionRows : $rows;

$displayRows = array_filter(
    $displayRows,
    fn($r) => ($r['section_key'] ?? '') !== 'about_text'
);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About • Gamebits</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="nav-container">
            <a class="nav-brand" href="/index.php">Gamebits</a>

            <ul class="nav-menu">
                <li class="nav-item"><a class="nav-link" href="/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Mini-Games</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="/about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/login.php">Log In</a></li>
            </ul>

            <form class="nav-search" action="#" method="get">
                <input type="search" placeholder="Search games…" aria-label="Search">
            </form>
        </div>
    </nav>
</header>

<main>
    <section class="about-section">
        <div class="about-container">

            <?php if ($aboutText !== ''): ?>
                <div class="content-block about-text">
                    <p><?= htmlspecialchars($aboutText) ?></p>
                </div>
            <?php endif; ?>

            <?php foreach ($displayRows as $row): ?>
                <div class="content-block">

                    <?php if (!empty($row['content_image_path'])): ?>
                        <?php
                            $imgPath = $row['content_image_path'];

                            if (strpos($imgPath, '..') === false) {
                                $imgSrc = (strpos($imgPath, '/') === 0)
                                    ? $imgPath
                                    : '/' . $imgPath;
                            } else {
                                $imgSrc = '';
                            }
                        ?>

                        <?php if ($imgSrc !== ''): ?>
                            <img
                                src="<?= htmlspecialchars($imgSrc) ?>"
                                alt="About section image"
                            >
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!empty($row['content_text'])): ?>
                        <div><?= htmlspecialchars($row['content_text']) ?></div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>

        </div>
    </section>
</main>

<footer class="footer">
    <p>&copy; 2024 Gamebits. Learn leadership by doing.</p>
</footer>

</body>
</html>


