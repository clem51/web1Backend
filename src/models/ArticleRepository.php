<?php

namespace BackOffice\models;

use \PDO;

class ArticleRepository extends AbstractRepository
{

    public function getAll(): array
    {
        # TODO select only necessary fields
        $sql = "SELECT A.id, A.name as article_name, A.id, C.*  FROM dashboard_db.articles A INNER JOIN content C on A.id= C.article";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
        return $this->oneToMany($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function oneToMany($data)
    {
        $result = [];

        foreach ($data as $record) {
            $contents = ['type' => $record['type'], 'value' => $record['value'], 'name' => $record['name']];
            $key = $record['article'];
            if (array_key_exists($key, $result)) {
                array_push($result[$key]['contents'], $contents);
            } else {
                $result[$key]['id'] = $record['id'];
                $result[$key]['name'] = $record['article_name'];
                $result[$key]['contents'] = [$contents];
            }
        }

        return $result;
    }

    /**
     * @param $data
     */
    public function create($data): void
    {
        $sql = "INSERT INTO articles (name) VALUES (:name)";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
        $stmt->execute();
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
        $sql = "DELETE FROM articles WHERE id = :id";
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