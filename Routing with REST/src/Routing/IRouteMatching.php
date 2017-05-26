<?php

namespace App\Routing;


interface IRouteMatching
{
    /**
     * Match and return route in collection according to URL.
     * @param string $url - request's url
     * @param string $requestMethod - request's method
     * @return IRoute - route instance
     */
    public function matchRouteByUrl(string $url, string $requestMethod): IRoute;

    /**
     * Match and return route in collection according to Name.
     * @param string $url - route's name
     * @param string $requestMethod - request's method
     * @return IRoute - route instance
     */
    public function matchRouteByName(string $name, string $requestMethod): IRoute;

    /**
     * Find most similar route in collection according to URL.
     * @param string $url - request's url
     * @return IRoute - route instance
     */
    public function getMostSimilarRoute(string $url): IRoute;

}