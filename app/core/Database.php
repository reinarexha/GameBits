<?php

class Database
{
    
    private string $server;
    private string $database;
    private string $username;
    private string $password;

   
    private ?PDO $conn = null;

    public function __construct()
    {
        
        $this->server = getenv('DB_SERVER') ?: 'YOUR_SQL_SERVER_NAME_OR_HOST';
        $this->database = getenv('DB_NAME') ?: 'YOUR_DATABASE_NAME';
        $this->username = getenv('DB_USER') ?: 'YOUR_DATABASE_USERNAME';
        $this->password = getenv('DB_PASS') ?: 'YOUR_DATABASE_PASSWORD';
    }

    /**
     * getConnection()
     *
     * Creates and returns a PDO instance for MSSQL.
     * - Sets error mode to exceptions (so errors are easier to debug)
     * - Uses UTF-8
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
   
        if ($this->conn instanceof PDO) {
            return $this->conn;
        }

      
        $dsn = 'sqlsrv:Server=' . $this->server . ';Database=' . $this->database . ';CharacterSet=UTF-8';

       
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

       
        try {
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            return $this->conn;
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
}

