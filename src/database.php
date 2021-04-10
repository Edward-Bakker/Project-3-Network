<?php
class Database
{
    private $dbConnection = null;

    public function __construct()
    {
        $ini = (object) parse_ini_file('../config.ini', true);
        $config = (object) $ini->database;
        $host = $config->db_host;
        $db = $config->db_name;
        $user = $config->db_user;
        $pass = $config->db_pass;

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
        $prepare = $this->dbConnection->prepare($query);
        if($prepare !== false)
            return $prepare;
        else
            exit('Error during prepare');
    }

    public function close()
    {
        $this->dbConnection->close();
    }
}
