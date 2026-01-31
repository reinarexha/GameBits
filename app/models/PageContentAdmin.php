<?php

require_once __DIR__ . '/../core/Database.php';

class PageContentAdmin
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all(): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT * FROM page_contents ORDER BY page ASC, id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }

    public function find(int $id): ?array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM page_contents WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function update(int $id, array $data, int $userId): bool
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("UPDATE page_contents SET content_text = ?, content_image_path = ?, updated_by = ?, updated_at = GETDATE() WHERE id = ?");
        return $stmt->execute([
            $data['content_text'] ?? null,
            $data['content_image_path'] ?? null,
            $userId,
            $id
        ]);
    }
}
