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
     * Find route according to handed URL and method. If the route is not recognized, find the most similar one and call
     * 404 page.
     * @param string $url - url to match up.
     * @param string $requestMethod - method of looked for route.
     * @return IRoute - route from collection
     */
    public function matchRoute(string $url, string $requestMethod = 'GET'): IRoute
    {
        foreach ($this->collection as $route) {
            $routeUrl = addcslashes($route->getUrl(), '/');
            if ($route->isFilterEnabled())
                $routeUrl .= '(?:\/|\?(?:\w{1,30}=\w{1,30})(?:&\w{1,30}=\w{1,30}){0,30})?';
            if (preg_match('/^' . $routeUrl . '$/', $url) && $route->getRequestMethod() === $requestMethod) {
                return $route;
            }
        }

        $mostSimilarRoute = $this->getMostSimilarRoute($url);
        $this->setGlobalSimilarRoute($mostSimilarRoute);

        return $this->matchRouteByName('pageNotFound');
    }

    /**
     * Compare every chunk of URL with routes URLs. Compute for every route Levenshtein distance. Return route with the
     * least distance.
     * @param string $url
     * @return IRoute
     */
    public function getMostSimilarRoute(string $url): IRoute
    {
        $regexChunks = '/' . '(?:\/)(\w{1,30}\b)(?![\+\{\[])' . '/';
        $routes = [];

        $urlChunks = [];
        preg_match_all($regexChunks, $url, $urlChunks, PREG_SET_ORDER);

        foreach ($this->collection as $indexRoute => $route) {
            $routeUrlChunks = [];
            preg_match_all($regexChunks, $route->getUrl(), $routeUrlChunks, PREG_SET_ORDER);

            $routes[$indexRoute] = $route;
            if (count($routeUrlChunks))
              $levenSum[$indexRoute] = 0;
            foreach ($routeUrlChunks as $index => $routeChunk) {
                    $levenSum[$indexRoute] += levenshtein($routeChunk[1], $urlChunks[$index][1]);
            }
        }

        $mostSimilarRoute = $routes[array_search(min($levenSum), $levenSum)];
        return $mostSimilarRoute;
    }

    /**
     * Add to global scope route. Associated with 'similarRoute' key.
     * @param IRoute $route
     */

    private function setGlobalSimilarRoute(IRoute $route): void
    {
        $GLOBALS['similarRoute'] = $route;
    }

    /**
     * Find route according to handed name and method.
     * @param string $name - name to match up.
     * @param string $requestMethod - method of looked for route.
     * @return IRoute - route from collection
     */
    public function matchRouteByName(string $routeName, string $requestMethod = 'GET'): IRoute
    {
        foreach ($this->collection as $route) {
            if ($route->getName() === $routeName && $route->getRequestMethod() === $requestMethod)
                return $route;
        }
        throw new \Exception('Route: ' . $routeName . ' is not defined.');
    }
}