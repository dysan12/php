<?php

require 'vendor/autoload.php';



$globalVarsCollection = new App\GlobalVars();
$logsCreator = new App\Logs\LogsCreator();

$routesCollection = new App\Routing\RoutesCollection();
//Adding two samples routes
$routesCollection->addItem(new App\Routing\Route('index', '/?', [
    'type' => 'site',
    'requestMethod' => 'GET',
    'controllerSet' => ['ControllerName', 'MethodName']
]));
$routesCollection->addItem(new App\Routing\Route('getUser', '/users/([\w%-]{1,30})', [
    'type' => 'resource',
    'requestMethod' => 'GET',
    'controllerSet' => ['ControllerName', 'MethodName']
]));

$router = new App\Routing\Router($routesCollection, $globalVarsCollection, $logsCreator);
$router->run('/' . $_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

$route = $router->getMatchedRoute();
$controller = new {$route->getControllerName()}();
$controller->{$route->getControllerMethod()}();