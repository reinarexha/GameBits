<?php

require_once __DIR__ . '/../core/Database.php';

class ContactMessage
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(array $data, ?int $userId): bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_by, updated_by) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['subject'] ?? null,
            $data['message'] ?? '',
            $userId,
            $userId
        ]);
    }

    public function all(): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }

    public function find(int $id): ?array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
