<?php

require_once __DIR__ . '/../core/Database.php';

class User
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all(): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }

    public function find(int $id): ?array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function update(int $id, array $data, int $adminUserId): bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ?, updated_by = ?, updated_at = GETDATE() WHERE id = ?");
        return $stmt->execute([
            $data['username'] ?? '',
            $data['email'] ?? '',
            $data['role'] ?? 'user',
            $adminUserId,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countAdmins(): int
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        return (int) $stmt->fetchColumn();
    }
}
