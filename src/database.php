<?php
class Database
{
    private $dbConnection = null;

    public function __construct()
    {
        $config = (object) parse_ini_file('../config.ini', true);
        $host = $config->database->db_host;
        $db = $config->database->db_name;
        $user = $config->database->db_user;
        $pass = $config->database->db_pass;

        try
        {
            $this->dbConnection = new mysqli($host, $user, $pass, $db);
        }
        catch (Exception $e)
        {
            exit($e->getMessage());
        }
    }

    public function connect()
    {
        return $this->dbConnection;
    }

    public function prepare($query)
    {
        return $this->dbConnection->prepare($query);
    }

    public function close()
    {
        $this->dbConnection->close();
    }
}
