<?php

require 'vendor/autoload.php';



$globalVarsCollection = new \src\GlobalVars();
$logsCreator = new \src\Logs\LogsCreator();

$routesCollection = new Webqms\Routing\RoutesCollection();
//Adding two samples routes
$routesCollection->addItem(new Webqms\Routing\Route('index', '/?', [
    'type' => 'site',
    'requestMethod' => 'GET',
    'controllerSet' => ['ControllerName', 'MethodName']
]));
$routesCollection->addItem(new \Webqms\Routing\Route('getUser', '/users/([\w%-]{1,30})', [
    'type' => 'resource',
    'requestMethod' => 'GET',
    'controllerSet' => ['ControllerName', 'MethodName']
]));

$router = new Webqms\Routing\Router($routesCollection, $globalVarsCollection, $logsCreator);
$router->run('/' . $_SERVER['REQUEST_URI']);

$route = $router->getMatchedRoute();
$controller = new {$route->getControllerName()}();
$controller->{$route->getControllerMethod()}();