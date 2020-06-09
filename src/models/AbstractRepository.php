<?php


namespace BackOffice\Models;


abstract class AbstractRepository
{
    protected $db;

    function __construct(Database $database)
    {
        $this->db = $database;
    }

}