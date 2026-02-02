<?php
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/ScoreRepositoryInterface.php';

class DbScoreRepository implements ScoreRepositoryInterface {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM scores ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM scores WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): array {
        $stmt = $this->db->prepare("
            INSERT INTO scores (user_id, game_id, score, created_by, updated_by)
            VALUES (:user_id, :game_id, :score, :created_by, :updated_by)
        ");

        $stmt->execute([
            ':user_id' => (int)($data['user_id'] ?? 0),
            ':game_id' => (int)($data['game_id'] ?? 0),
            ':score' => (int)($data['score'] ?? 0),
            ':created_by' => $data['created_by'] ?? null,
            ':updated_by' => $data['updated_by'] ?? null,
        ]);

        $id = (int)$this->db->lastInsertId();
        return $this->findById($id) ?? ['id' => $id] + $data;
    }

    public function findByGameId(int $gameId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM scores
            WHERE game_id = :game_id
            ORDER BY score DESC, created_at ASC
        ");
        $stmt->execute([':game_id' => $gameId]);
        return $stmt->fetchAll();
    }

    public function getTopScores(int $gameId, int $limit = 10): array {
    $limit = max(1, (int)$limit);

    $sql = "
        SELECT *
        FROM scores
        WHERE game_id = :game_id
        ORDER BY score DESC, created_at ASC
        LIMIT $limit
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([':game_id' => $gameId]);
    return $stmt->fetchAll();
}


    // join
    public function findAllWithGames(): array {
        $stmt = $this->db->query("
    SELECT 
      s.id,
      s.user_id,
      u.username,
      s.game_id,
      g.title AS game_title,
      s.score,
      s.created_at
    FROM scores s
    JOIN users u ON u.id = s.user_id
    JOIN games g ON g.id = s.game_id
    ORDER BY s.score DESC, s.created_at ASC
");

        $rows = $stmt->fetchAll();

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'id' => (int)$r['id'],
                'user_id' => (int)$r['user_id'],
                'username' => $r['username'] ?? 'Anonymous',
                'game_id' => (int)$r['game_id'],
                'score' => (int)$r['score'],
                'created_at' => $r['created_at'],
                'game' => [
                    'title' => $r['game_title'] ?? 'Unknown Game',
                ],
            ];
        }
        return $out;
    }

    // admin mod
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE scores
            SET score = :score,
                updated_by = :updated_by
            WHERE id = :id
        ");
        $stmt->execute([
            ':id' => $id,
            ':score' => (int)($data['score'] ?? 0),
            ':updated_by' => $data['updated_by'] ?? null,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM scores WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}