<?php

namespace Webqms\Routing;

interface ICollection
{
    public function addRoute(IRoute $route);

    public function matchRoute(string $url, string $requestMethod): IRoute;

    public function matchRouteByName(string $routeName, string $requestMethod): IRoute;

}