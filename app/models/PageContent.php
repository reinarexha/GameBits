<?php

require_once __DIR__ . '/../core/Database.php';

class PageContent
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function get(string $page, string $key): ?array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM page_contents WHERE page = ? AND section_key = ?");
        $stmt->execute([$page, $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getText(string $page, string $key, string $default = ''): string
    {
        $row = $this->get($page, $key);
        if ($row === null || !isset($row['content_text'])) {
            return $default;
        }
        return (string) $row['content_text'];
    }

    public function getAllByPage(string $page): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM page_contents WHERE page = ? ORDER BY id ASC");
        $stmt->execute([$page]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }
}
