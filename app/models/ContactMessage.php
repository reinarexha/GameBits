<?php

require_once __DIR__ . '/../core/Database.php';

class ContactMessage
{
    private static function pdo(): PDO
    {
        $db = new Database();
        return $db->getConnection();
    }

    // Used by your contact form when creating a message
    public static function create(array $data, ?int $userId = null): bool
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare("
            INSERT INTO contact_messages (name, email, subject, message, created_by, updated_by)
            VALUES (:name, :email, :subject, :message, :created_by, :updated_by)
        ");

        return $stmt->execute([
            ':name'       => trim($data['name'] ?? ''),
            ':email'      => trim($data['email'] ?? ''),
            ':subject'    => ($data['subject'] ?? null) !== '' ? trim($data['subject']) : null,
            ':message'    => trim($data['message'] ?? ''),
            ':created_by' => $userId,
            ':updated_by' => $userId,
        ]);
    }

    // Admin list
    public static function getAll(): array
    {
        $pdo = self::pdo();
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows ?: [];
    }

    // Admin view
    public static function findById(int $id): ?array
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // Admin action: mark read
    public static function markAsRead(int $id): bool
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Admin action: delete
    public static function delete(int $id): bool
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

