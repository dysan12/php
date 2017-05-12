<?php

namespace Webqms\Routing;
/**
 * Class RoutesCollection collects and manages routes.
 * @package Webqms\Routing
 */
class RoutesCollection implements ICollection
{
    private $collection = [];

    public function addRoute(IRoute $route): void
    {
        $this->collection[] = $route;

    }

    /**
     * Find route according to handed URL and method.
     * @param string $url - url to match up.
     * @param string $requestMethod - method of looked for route.
     * @return IRoute - route from collection
     */
    public function matchRouteByUrl(string $url, string $requestMethod = 'GET'): IRoute
    {
        foreach ($this->collection as $route) {
           // levenshtein($url, $route);
            $routeUrl = addcslashes($route->getUrl(), '/');

            if ($route->isFilterEnabled())
                $routeUrl .= '';//TODO finish regular expression for filter query
            if (preg_match('/^' . $routeUrl . '$/', $url) && $route->getRequestMethod() === $requestMethod) {
                return $route;
            }
        }

        return $this->matchRouteByName('pageNotFound');
    }

    /**
     * Find route according to handed name and method.
     * @param string $name - name to match up.
     * @param string $requestMethod - method of looked for route.
     * @return IRoute - route from collection
     */
    private function matchRouteByName(string $routeName, string $requestMethod = 'GET'): IRoute
    {
        foreach ($this->collection as $route) {
            if ($route->getName() === $routeName && $route->getRequestMethod() === $requestMethod)
                return $route;
        }
        throw new \Exception('Route: ' . $routeName . ' is not defined.');
    }
}