<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Helpers\EnvHelper;

class Database {

    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct() {
        try {
            $host = EnvHelper::getDbHost();
            $port = EnvHelper::getDbPort();
            $dbname = EnvHelper::getDbName();
            $username = EnvHelper::getDbUser();
            $password = EnvHelper::getDbPassword();

            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};";

            $this->connection = new PDO(
                $dsn,
                $username,
                $password,
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
