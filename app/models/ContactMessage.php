<?php
require_once __DIR__ . '/../includes/Database.php';

class ContactMessage {
    public static function create(array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO contact_messages (name, email, subject, message)
            VALUES (:name, :email, :subject, :message)
        ");
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':subject' => $data['subject'] ?? null,
            ':message' => $data['message'],
        ]);
    }

    public static function getAll(): array {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function markAsRead(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function findById(int $id): ?array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM contact_messages WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
