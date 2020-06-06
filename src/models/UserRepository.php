<?php

namespace BackOffice\Models;
use BackOffice\Models\Database;
use \PDO;

class UserRepository{
    private $db;

    function __construct(Database $database){
        $this->db = $database;
    }

    function getUserByUsername($username){
        $sql = "SELECT id, username, password FROM users WHERE username = :username";      
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        if($row){
          return $row;
        }
        throw new Exception('User doesn\'t exists');
      }
}