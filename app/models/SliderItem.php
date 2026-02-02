<?php

require_once __DIR__ . '/../core/Database.php';

class SliderItem
{
    public int $id;
    public string $title;
    public ?string $subtitle = null;
    public string $image_path;
    public ?string $created_at = null;

    public function save(): bool
    {
        $pdo = Database::getConnection();

        $sql = "INSERT INTO slider_items (title, subtitle, image_path)
                VALUES (:title, :subtitle, :image_path)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':title' => $this->title,
            ':subtitle' => $this->subtitle,
            ':image_path' => $this->image_path,
        ]);
    }

    public static function getAll(): array
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->query("
            SELECT id, title, subtitle, image_path, created_at
            FROM slider_items
            ORDER BY created_at DESC
        ");

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $items = [];
        foreach ($rows as $row) {
            $item = new self();
            $item->id = (int) $row['id'];
            $item->title = (string) $row['title'];
            $item->subtitle = $row['subtitle'] !== null ? (string) $row['subtitle'] : null;
            $item->image_path = (string) $row['image_path'];
            $item->created_at = $row['created_at'] ?? null;
            $items[] = $item;
        }

        return $items;
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare("DELETE FROM slider_items WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function update(int $id, string $title, ?string $subtitle, string $imagePath): bool
    {
        $pdo = Database::getConnection();

        $sql = "UPDATE slider_items
                SET title = :title,
                    subtitle = :subtitle,
                    image_path = :image_path
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':subtitle' => $subtitle,
            ':image_path' => $imagePath,
        ]);
    }
}
