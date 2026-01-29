<?php

/**
 * Database class - gives us a PDO connection to MySQL.
 * Replace host, dbname, user, password with your own for the project to work.
 */
class Database
{
    /** @var PDO|null */
    private static $connection = null;

    /**
     * Returns a single PDO connection. Creates it once, then reuses it.
     * @return PDO
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            $host = 'localhost';
            $dbname = 'gamebits';
            $user = 'root';
            $password = '';
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            self::$connection = new PDO($dsn, $user, $password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }
}
