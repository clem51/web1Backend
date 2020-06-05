<?php

class Database
{
  private $pdo;

  function __construct($dns, $user, $password){
    try{
      $this->pdo = new PDO( $dns, $user, $password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }
  }

  function getUserByUsername($username){
    $sql = "SELECT id, username, password FROM users WHERE username = :username";      
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    if($row){
      return $row;
    }
    throw new Exception('User doesn\'t exists');
  }
}
?>