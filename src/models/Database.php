<?php

namespace BackOffice\models;

use \PDO;
use PDOException;

class Database
{

    public PDO $connection;

    function __construct(string $host, string $dbname, string $user, string $password, int $port = 3306, string $driver = "mysql")
    {
        try {
            $dns = "{$driver}:host={$host};dbname=${dbname};port={$port}";
            $this->connection = new PDO($dns, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("ERROR: Could not connect. " . $e->getMessage());
        }
    }
}
