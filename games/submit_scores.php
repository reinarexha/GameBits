<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../repositories/DbScoreRepository.php';
require_once __DIR__ . '/../repositories/DbGameRepository.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
  exit;
}

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$userId = (int)($_SESSION['user']['id'] ?? 0);
if ($userId <= 0) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'error' => 'Not logged in']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$score = (int)($input['score'] ?? 0);
$gameKey = trim($input['game'] ?? ''); // "snake" etc

if ($score < 0) $score = 0;
if ($gameKey === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Missing game']);
  exit;
}


$gameRepo = new DbGameRepository();
$games = $gameRepo->findAll();

$gameId = 0;
foreach ($games as $g) {
  $playUrl = (string)($g['play_url'] ?? '');
  if ($playUrl !== '' && stripos($playUrl, "/games/$gameKey/") !== false) {
    $gameId = (int)$g['id'];
    break;
  }
}

if ($gameId <= 0) {
  http_response_code(404);
  echo json_encode(['ok' => false, 'error' => 'Game not found in DB (check play_url)']);
  exit;
}

$scoreRepo = new DbScoreRepository();
$row = $scoreRepo->create([
  'user_id' => $userId,
  'game_id' => $gameId,
  'score' => $score,
  'created_by' => $userId,
  'updated_by' => $userId,
]);

echo json_encode(['ok' => true, 'score_id' => $row['id'] ?? null]);