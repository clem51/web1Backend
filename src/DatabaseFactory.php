<?php

namespace BackOffice;

use BackOffice\Models\Database;

class DatabaseFactory
{
    public  static function getDevelopmentServerConnection(): Database
    {
        return new Database("localhost", "dashboard_db", 'root', 'root');
    }

    public static function getProductionServerConnection(): Database
    {
        $opts = parse_url(getenv('DATABASE_URL'));
        return new Database($opts["host"], ltrim($opts["path"], '/'), $opts["user"], $opts["pass"], $opts["port"], "pgsql");
    }
}