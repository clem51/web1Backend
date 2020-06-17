<?php


namespace BackOffice\models;
use \PDO;

abstract class AbstractRepository
{
    protected Database $db;

    function __construct(Database $database)
    {
        $this->db = $database;
    }

    protected function insertContent(int $id, array $content): void
    {
        foreach ($content as $value) {
            $sql = "INSERT INTO content (name, type, value, article) VALUES (:name, :type, :value, :article)";
            $stmt = $this->db->connection->prepare($sql);
            $stmt->bindParam(":type", $value["type"], PDO::PARAM_STR);
            $stmt->bindParam(":value", $value["value"], PDO::PARAM_STR);
            $stmt->bindParam(":article", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $value["name"], PDO::PARAM_STR);
            $stmt->execute();
        }
    }

}