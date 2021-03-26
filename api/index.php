<?php
    require '../start.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application-json; charset=UTF-8");
    header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if(!isset($uri[1]) || $uri[1] !== 'api')
    {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $function = null;
    if (isset($uri[2]))
    {
        $function = $uri[2];
    }

    $controller = new Post($requestMethod, $function);
    $controller->processRequest();
