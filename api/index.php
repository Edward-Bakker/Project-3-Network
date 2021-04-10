<?php
require '../start.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application-json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];
$function = null;
if (isset($uri[1]))
{
    $function = $uri[1];
}

$controller = new Post($requestMethod, $function);

$config = (object) parse_ini_file('../config.ini', true);
if ($config->authorization)
{
    $headers = array_change_key_case(getallheaders(), CASE_LOWER);
    if ($headers['pb-api-ident'] !== null || $headers['pb-api-secret'] !== null)
    {
        $id = (int) $headers['pb-api-ident'];
        $secret = $headers['pb-api-secret'];
        $authorization = new Authorization($id, $secret);
        $ratelimiting = new Ratelimiting($id);
        if ($authorization->Authenticate())
        {
            if ($config->ratelimit)
            {
                $ratelimiting->rateLimit();
            }
            $controller->processRequest();
        }
        else
        {
            header('HTTP/1.1 401 Unauthorized');
            die();
        }
    }
    else
    {
        header('HTTP/1.1 401 Unauthorized');
        die();
    }
}
else
{
    if ($config->ratelimit)
    {
        error_log('Ratelimiting is enabled, but requires authorization to do so.', 0);
    }
    $controller->processRequest();
}
