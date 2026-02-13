<?php

namespace app\core;

use PDO;
use PDOException;

class Database
{
    private $server = "localhost";
    private $user = "root";
    private $password = "";
    private $db_name = "thoth_lms";
    private $port = "3308";

    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->server};port={$this->port};dbname={$this->db_name};charset=utf8mb4",
                $this->user,
                $this->password
            );

            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {
            die("Database connection error");
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}