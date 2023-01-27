<?php
session_start();

error_reporting(error_level: E_ALL & ~E_DEPRECATED);
ini_set(option: 'display_errors', value: '1');

use Controller\Controller;

include __DIR__ . '/autoload.php';

$query = $_SERVER['QUERY_STRING'];
$path = $_SERVER['REQUEST_URI'];
$arrayPath = explode('/', $path);
$controllerName = 'Controller\\' . ucfirst(substr(ucfirst($arrayPath[count($arrayPath) - 2]), 2)) . 'Controller';
$action = explode('&', $arrayPath[count($arrayPath) - 1])[0];

if (!method_exists($controllerName, $action)  ){
    $action = null;
}

if (!empty($query)) {
    $params = explode("&", $query);
} else {
    $params = [];
}
try {
    /** @var Controller $controller */
    $controller = new $controllerName;

    if (empty($action)) {
        echo $controller->action($params);
        return;
    }

    echo $controller->$action($params);

} catch (Exception $error) {
    echo $error->getMessage();
}
