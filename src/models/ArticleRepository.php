<?php

namespace BackOffice\models;
use \PDO;

class ArticleRepository extends AbstractRepository {

    public function getAll($mode=PDO::FETCH_OBJ) : array{
        # TODO select only necessary fields
        $sql = "SELECT * FROM articles ";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll($mode);
    }

    /**
     * @param $data
     */
    public function create($data) : void{
        $sql = "INSERT INTO articles (name) VALUES (:name)";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
        $stmt->execute();
    }

    public function update($data, int $id): void{
        $sql = "UPDATE articles SET name = :name WHERE id=:id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(int $id): void{
        $sql = "DELETE FROM articles WHERE id = :id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function getById(int $id){
        $sql = "SELECT * FROM articles WHERE id= :id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":id",$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(); 
    }
}