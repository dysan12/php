<?php

namespace App\Routing;

use App\Exceptions\CollectionException;
use App\Exceptions\NotFoundException;
use Currency\Collections\ICollection;

/**
 * Class RouteMatching responsible for matching route from collection.
 * @package App\Routing
 */
class RouteMatching implements IRouteMatching
{
    private $collection = [];

    public function __construct(ICollection $collection)
    {
        $this->collection = $collection->getCollection();

        foreach ($this->collection as $route) {
            if (!($route instanceof IRoute))
                throw new CollectionException('Conveyed collection includes non-IRoute instance/s.');
        }
    }

    /**
     * Find route according to handed URL and method.
     * @param string $url - url to match up.
     * @param string $requestMethod - method of looked for route.
     * @throws NotFoundException - if couldn't find any matching route in collection
     * @return IRoute - route from collection
     */
    public function matchRouteByUrl(string $url, string $requestMethod = 'GET'): IRoute
    {
        foreach ($this->collection as $route) {
            $routeUrl = addcslashes($route->getUrl(), '/');
            if ($route->isFilterEnabled())
                $routeUrl .= '(?:\/|\?(?:\w{1,30}=\w{1,30})(?:&\w{1,30}=\w{1,30}){0,30})?';

            if (preg_match('/^' . $routeUrl . '$/', $url) && $route->getRequestMethod() === $requestMethod) {
                return $route;
            }
        }
        throw new NotFoundException('Route for URL "' . $url . '" is not included in loaded collection.');
    }

    /**
     * Compare every chunk of URL with routes URLs. Compute for every route Levenshtein distance. Return route with the
     * least distance.
     */
    public function getMostSimilarRoute(string $url): IRoute
    {
        $regexChunks = '/(?:\/)(\w{1,30}\b)(?![\+\{\[])/';
        $routes = [];

        preg_match_all($regexChunks, $url, $urlChunks);

        foreach ($this->collection as $indexRoute => $route) {
            preg_match_all($regexChunks, $route->getUrl(), $routeUrlChunks);

            $routes[$indexRoute]['route'] = $route;
            $routes[$indexRoute]['levenSum'] = $this->computeArraysLevenshtein($routeUrlChunks[1], $urlChunks[1]);
        }
        $levenSumColumn = array_column($routes, 'levenSum');
        $lowestLevenIndex = array_search(min($levenSumColumn), $levenSumColumn);

        return $routes[$lowestLevenIndex]['route'];
    }

    /**
     * Take two arrays of strings and compute its levenshtein distance. Every element of the first array is compare to
     * element with the same index in second array.
     * @param array $arrayOne
     * @param array $arrayTwo
     * @return int - levenshtein distance
     */
    private function computeArraysLevenshtein(array $arrayOne, array $arrayTwo): int
    {
        $levenshteinSum = 0;

        foreach ($arrayOne as $index => $chunk) {
            $levenshteinSum += levenshtein($chunk, $arrayTwo[$index]);
        }
        if (count($arrayOne) === 0)
            return 100;
        else
            return $levenshteinSum;
    }

    /**
     * Find route according to handed name and method.
     * @param string $name - name to match up.
     * @param string $requestMethod - method of route looked for.
     * @throws NotFoundException - if couldn't find any matching route in collection
     * @return IRoute - route from collection
     */
    public function matchRouteByName(string $routeName, string $requestMethod = 'GET'): IRoute
    {
        foreach ($this->collection as $route) {
            if ($route->getName() === $routeName && $route->getRequestMethod() === $requestMethod)
                return $route;
        }
        throw new NotFoundException('Route "' . $routeName . '" is not included in loaded collection.');
    }
}