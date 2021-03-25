<?php
    class Start
    {
        public static function register()
        {
            spl_autoload_register(function ($className)
            {
                $file = strtolower($className) . '.php';
                if(file_exists($file))
                {
                    require $file;
                    return true;
                }
                return false;
            });
        }
    }
    Start::register();
    $database = new Database();
    $dbConnection = $database->connect();
