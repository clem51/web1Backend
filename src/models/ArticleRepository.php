<?php

namespace BackOffice\models;

use \PDO;

class ArticleRepository extends AbstractRepository
{

    public function getAll(): array
    {
        $sql = "SELECT A.id as article_id , A.name as article_name, C.*  
                FROM articles A 
                LEFT JOIN content C on A.id = C.article";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
        return $this->oneToMany($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function oneToMany($data)
    {
        $result = [];
        foreach ($data as $record) {
            $contents = ['type' => $record['type'], 'value' => $record['value'], 'name' => $record['name']];
            $key = $record['article_id'];
            if (array_key_exists($key, $result)) {
                array_push($result[$key]['contents'], $contents);
            } else {
                $result[$key]['article_id'] = $record['article_id'];
                $result[$key]['name'] = $record['article_name'];
                $result[$key]['contents'] = [$contents];
            }
        }

        return $result;
    }

    /**
     * @param $name
     * @param $content
     */
    public function create($name, $content): void
    {
        $sql = "INSERT INTO articles (name) VALUES (:name)";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $id = $this->db->connection->lastInsertId();

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

    public function update($data, int $id): void
    {
        $sql = "UPDATE articles SET name = :name WHERE id=:id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $sql = "DELETE A,C FROM articles A Left JOIN content C ON A.id = C.article WHERE A.id= :id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM articles WHERE id= :id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
}