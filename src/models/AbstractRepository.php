<?php


namespace BackOffice\models;


abstract class AbstractRepository
{
    protected $db;

    function __construct(Database $database)
    {
        $this->db = $database;
    }

}