<?php
require "../bootstrap.php";
require "../src/DAO/ContactDAO.php";
require "../src/controller/ContactController.php";

use src\controller\ContactController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // The request is using the POST method
    header("HTTP/1.1 200 OK");
    return;
}

if ($uri[1] !== 'contact') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$contactId = null;
if (isset($uri[2])) {
    $contactId = (int)$uri[2];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new ContactController($dbConnection, $requestMethod, $contactId);
$controller->processRequest();