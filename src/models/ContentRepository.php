<?php

namespace BackOffice\models;

use \PDO;

class ContentRepository extends AbstractRepository
{
    public function delete(int $id): void
    {
        $sql = "DELETE FROM content WHERE id= :id LIMIT 1";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function create(int $id, $content): void
    {
        $this->insertContent($id, $content);
    }
}
