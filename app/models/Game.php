<?php

require_once __DIR__ . '/../core/Database.php';

class Game
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all(): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT * FROM games ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }

    public function find(int $id): ?array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data, int $userId): int
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("INSERT INTO games (title, description, image_path, created_by, updated_by, created_at, updated_at) OUTPUT INSERTED.id VALUES (?, ?, ?, ?, ?, GETDATE(), GETDATE())");
        $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? null,
            $data['image_path'] ?? null,
            $userId,
            $userId
        ]);
        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, array $data, int $userId): bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("UPDATE games SET title = ?, description = ?, image_path = ?, updated_by = ?, updated_at = GETDATE() WHERE id = ?");
        return $stmt->execute([
            $data['title'] ?? '',
            $data['description'] ?? null,
            $data['image_path'] ?? null,
            $userId,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
