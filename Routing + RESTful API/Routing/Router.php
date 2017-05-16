<?php
namespace Webqms\Routing;

/**
 * Class Router responsible for extracting data from request and getting appropriate Route instance.
 * @package Webqms\Routing
 */
class Router implements IRouter
{
    private $collection;
    private $route;
    private $url;

    public function __construct(ICollection $routesCollection)
    {
        $this->collection = $routesCollection;
    }

    public function runRouter(string $requestUrl, string $requestMethod): void
    {
        $this->url = $requestUrl;

        $this->route = $this->collection->matchRoute($requestUrl, $requestMethod);
    }

    public function getRoute(): IRoute
    {
        if ($this->route instanceof IRoute)
            return $this->route;
        else
            throw new \Exception('Router: Route wasn\'t earlier initialized.');
    }

    private function extractUrlData()
}