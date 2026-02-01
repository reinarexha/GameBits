<?php

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/NewsRepositoryInterface.php';

class DbNewsRepository implements NewsRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM news ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): array
    {
        $userId = $_SESSION['user']['id'] ?? null;

        $stmt = $this->pdo->prepare("
            INSERT INTO news (title, body, attachment_path, attachment_type, created_by, updated_by)
            VALUES (:title, :body, :attachment_path, :attachment_type, :created_by, :updated_by)
        ");

        $stmt->execute([
            ':title'           => trim($data['title'] ?? ''),
            ':body'            => trim($data['body'] ?? ''),
            ':attachment_path' => $data['attachment_path'] ?? null,
            ':attachment_type' => $data['attachment_type'] ?? null, // 'image' or 'pdf'
            ':created_by'      => $userId,
            ':updated_by'      => $userId,
        ]);

        $newId = (int)$this->pdo->lastInsertId();
        return $this->findById($newId) ?? ['id' => $newId];
    }

    public function update(int $id, array $data): bool
    {
        $userId = $_SESSION['user']['id'] ?? null;

        $stmt = $this->pdo->prepare("
            UPDATE news
            SET title = :title,
                body = :body,
                attachment_path = :attachment_path,
                attachment_type = :attachment_type,
                updated_by = :updated_by,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'              => $id,
            ':title'           => trim($data['title'] ?? ''),
            ':body'            => trim($data['body'] ?? ''),
            ':attachment_path' => $data['attachment_path'] ?? null,
            ':attachment_type' => $data['attachment_type'] ?? null,
            ':updated_by'      => $userId,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $query): array
    {
        $q = '%' . $query . '%';
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM news
            WHERE title LIKE :q OR body LIKE :q
            ORDER BY created_at DESC
        ");
        $stmt->execute([':q' => $q]);
        return $stmt->fetchAll();
    }
}