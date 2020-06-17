<?php

namespace BackOffice\models;

use \PDO;
use stdClass;

class UserRepository extends AbstractRepository
{

    /**
     * get the username and password form database
     * @param string $username
     * @return stdClass
     */
    function getUserByUsername(string $username): stdClass
    {
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}