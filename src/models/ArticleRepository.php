<?php

namespace BackOffice\models;

use \PDO;

class ArticleRepository extends AbstractRepository
{
    /**
     * get all articles with the associate content
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT A.id as article_id , A.name as article_name, C.*  
                FROM articles A 
                LEFT JOIN content C on A.id = C.article";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
        return $this->oneToMany($stmt->fetchAll(PDO::FETCH_ASSOC));
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

        $this->insertContent($id, $content);
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

    public function getById(int $id): array
    {
        $sql = "SELECT A.id as article_id , A.name as article_name, C.*  
                FROM articles A 
                LEFT JOIN content C on A.id = C.article WHERE A.id=:id";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->oneToMany($stmt->fetchAll(PDO::FETCH_ASSOC))[$id];
    }


    /**
     * Format results to appear as a one to many orm operation
     * @param array $data
     * @return array
     */
    private function oneToMany(array $data)
    {
        $result = [];
        foreach ($data as $record) {
            $contents = ['type' => $record['type'], 'value' => $record['value'], 'name' => $record['name'], 'content_id' => $record['id']];
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
}