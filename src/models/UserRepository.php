<?php

namespace BackOffice\Models;

use \PDO;

class UserRepository extends AbstractRepository
{


    function getUserByUsername(string $username)
    {
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}