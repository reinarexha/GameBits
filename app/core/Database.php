<?php
require_once __DIR__ . '/../../includes/config.php';

class Database
{
    private static ?PDO $conn = null;

    public static function getConnection(): PDO
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $dsn = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

        try {
            self::$conn = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            return self::$conn;
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
}


