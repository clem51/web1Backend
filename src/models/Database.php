<?php

namespace BackOffice\Models;
use \PDO;

class Database{

  public $pdo;

  function __construct($dns, $user, $password){
    try{
      $this->pdo = new PDO( $dns, $user, $password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }
  }
}
