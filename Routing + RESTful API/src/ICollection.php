<?php

namespace Webqms\Routing;

interface ICollection
{
    public function addRoute(IRoute $route);

    public function matchRouteByUrl(string $url, string $requestMethod): IRoute;

}