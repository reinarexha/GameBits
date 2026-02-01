<?php

require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/GameRepositoryInterface.php';

class DbGameRepository implements GameRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM games ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM games WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): array
    {
        $userId = $_SESSION['user']['id'] ?? null;

        $stmt = $this->pdo->prepare("
            INSERT INTO games (title, description, image_path, created_by, updated_by)
            VALUES (:title, :description, :image_path, :created_by, :updated_by)
        ");

        $stmt->execute([
            ':title'       => trim($data['title'] ?? ''),
            ':description' => $data['description'] ?? null,
            ':image_path'  => $data['image_path'] ?? null,
            ':created_by'  => $userId,
            ':updated_by'  => $userId,
        ]);

        $newId = (int)$this->pdo->lastInsertId();
        return $this->findById($newId) ?? ['id' => $newId];
    }

    public function update(int $id, array $data): bool
    {
        $userId = $_SESSION['user']['id'] ?? null;

        $stmt = $this->pdo->prepare("
            UPDATE games
            SET title = :title,
                description = :description,
                image_path = :image_path,
                updated_by = :updated_by,
                updated_at = NOW()
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'          => $id,
            ':title'       => trim($data['title'] ?? ''),
            ':description' => $data['description'] ?? null,
            ':image_path'  => $data['image_path'] ?? null,
            ':updated_by'  => $userId,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM games WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $query): array
    {
        $q = '%' . $query . '%';
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM games
            WHERE title LIKE :q OR description LIKE :q
            ORDER BY created_at DESC
        ");
        $stmt->execute([':q' => $q]);
        return $stmt->fetchAll();
    }
}


