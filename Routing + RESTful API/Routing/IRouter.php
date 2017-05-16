<?php
namespace Webqms\Routing;


interface IRouter
{
    public function runRouter(string $requestUrl, string $requestMethod);

    public function getRoute(): IRoute;
}