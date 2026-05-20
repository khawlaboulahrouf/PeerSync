<?php

class Database {

    private static $instance = null;

    private $host = "localhost";
    private $dbname = "peersync_db";
    private $username = "root";
    private $password = "";

    private $connection;

    private function __construct()
    {
        try {

            $this->connection = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->dbname,
                $this->username,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch(PDOException $e) {

            die("Erreur connexion : " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if(self::$instance == null) {

            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}