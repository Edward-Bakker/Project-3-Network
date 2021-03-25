<?php
    class Database
    {
        private $dbConnection = null;

        public function __construct()
        {
            $config = (object) parse_ini_file('../config.ini');
            $host = $config->db_host;
            $db = $config->db_name;
            $user = $config->db_user;
            $pass = $config->db_pass;

            try {
                $this->dbConnection = new mysqli($host, $user, $pass, $db);
            }
            catch(Exception $e)
            {
                exit($e->getMessage());
            }
        }

        public function connect()
        {
            return $this->dbConnection;
        }
    }
