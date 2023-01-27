<?php
session_start();

error_reporting(error_level: E_ALL & ~E_DEPRECATED);
ini_set(option: 'display_errors', value: '1');

use Controller\Controllers;

include __DIR__ . '/autoload.php';

$query = $_SERVER['QUERY_STRING'];
$path = $_SERVER['REQUEST_URI'];
$arrayPath = explode('/', $path);
$controllerName = 'Controller\\' . substr(ucfirst($arrayPath[count($arrayPath) - 2]), 2) . 'Controllers';
$action = explode('&', $arrayPath[count($arrayPath) - 1])[0];


if (!empty($query)) {
    $params = explode("&", $query);
} else {
    $params = null;
}
try {
    /** @var Controllers $index */
    $index = new $controllerName;
    if (empty($action)) {
        echo $index->action($params);
        return;
    }

    echo $index->$action($params);

} catch (Exception $error) {
    echo $error->getMessage();
}







