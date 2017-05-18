<?php
namespace src\Routing;


interface IRouter
{
    public function run(string $requestUrl, string $requestMethod);

    public function getRoute(): IRoute;
}