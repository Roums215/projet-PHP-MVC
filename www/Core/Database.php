<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {

    private static ?Database $instance = null;

    private PDO $connection;


    private string $host     = "db";      
    private int    $port = 5432;       
    private string $dbname   = "devdb";
    private string $username = "devuser";
    private string $password = "devpass";


    private function __construct() {
        try {

            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname};";


            $this->connection = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }


    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    public function getConnection(): PDO {
        return $this->connection;
    }
}
