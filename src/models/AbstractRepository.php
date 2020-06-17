<?php


namespace BackOffice\models;


abstract class AbstractRepository
{
    protected Database $db;

    function __construct(Database $database)
    {
        $this->db = $database;
    }

}