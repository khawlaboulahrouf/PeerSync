<?php

/**
 * Singleton PDO — point d'accès unique à la base aidesync.
 * Toute requête SQL passe par les Repositories, pas par cette classe.
 */
class Database
{
    private static ?Database $instance = null;

    private string $host = 'localhost';
    private string $dbname = 'aidesync';
    private string $username = 'root';
    private string $password = '';

    private PDO $connection;

    private function __construct()
    {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur connexion base de données : ' . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
