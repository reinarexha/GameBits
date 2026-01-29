
<?php
session_start();

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../models/ContactMessage.php';

// role check 
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  header('Location: ' . BASE_URL . '/auth/signin.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = (int)($_POST['id'] ?? 0);

  if (isset($_POST['mark_read']) && $id > 0) {
    ContactMessage::markAsRead($id);
  }

  if (isset($_POST['delete']) && $id > 0) {
    ContactMessage::delete($id);
  }

  header('Location: ' . BASE_URL . '/admin/messages.php');
  exit;
}

// list
$messages = ContactMessage::getAll();
$viewId = (int)($_GET['view'] ?? 0);
$selected = $viewId ? ContactMessage::findById($viewId) : null;

$pageTitle = 'Messages';
$currentPage = 'messages';
include __DIR__ . '/../includes/admin_header.php';
?>

<section class="hero admin-hero">
  <h1 class="hero-title">Contact Messages</h1>
  <p class="hero-sub">Read and manage messages sent from Contact form.</p>
</section>

<section class="admin-wrap">
  <div class="admin-two-col">
    <div>
      <h2 class="section-title">Inbox</h2>

      <?php if (empty($messages)): ?>
        <p class="admin-empty">No messages yet.</p>
      <?php else: ?>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Status</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th class="right">Open</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($messages as $m): ?>
                <tr>
                  <td><?= ((int)$m['is_read'] === 1) ? 'Read' : 'New' ?></td>
                  <td><?= htmlspecialchars($m['name']) ?></td>
                  <td><?= htmlspecialchars($m['email']) ?></td>
                  <td><?= htmlspecialchars(date('M d, Y', strtotime($m['created_at']))) ?></td>
                  <td class="right">
                    <a class="btn-primary btn-small" href="<?= BASE_URL ?>/admin/messages.php?view=<?= (int)$m['id'] ?>">View</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <div>
      <h2 class="section-title">Message</h2>

      <?php if (!$selected): ?>
        <p class="admin-empty">Select a message to view details.</p>
      <?php else: ?>
        <div class="admin-card">
          <p><strong>Name:</strong> <?= htmlspecialchars($selected['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($selected['email']) ?></p>
          <p><strong>Subject:</strong> <?= htmlspecialchars($selected['subject'] ?? '-') ?></p>
          <p><strong>Date:</strong> <?= htmlspecialchars(date('M d, Y', strtotime($selected['created_at']))) ?></p>
          <hr class="admin-hr">
          <p><?= nl2br(htmlspecialchars($selected['message'])) ?></p>

          <div class="admin-card-actions">
            <?php if ((int)$selected['is_read'] === 0): ?>
              <form method="post" class="admin-inline-form">
                <input type="hidden" name="id" value="<?= (int)$selected['id'] ?>">
                <button class="btn-primary btn-small" name="mark_read" value="1" type="submit">Mark as read</button>
              </form>
            <?php endif; ?>

            <form method="post" class="admin-inline-form" onsubmit="return confirm('Delete this message?');">
              <input type="hidden" name="id" value="<?= (int)$selected['id'] ?>">
              <button class="btn-primary btn-small btn-danger" name="delete" value="1" type="submit">Delete</button>
            </form>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../includes/admin_footer.php'; ?>
