<?php

namespace App\Routing;

use App\Logs\ICreator;
use App\Exceptions\CollectionException;
use App\Exceptions\NotFoundException;

/**
 * Class Router controller. Initialize matching routes and extracting data from request.
 * @package App\Routing
 */
class Router
{
    private $routesCollection;
    private $globalVarsCollection;
    private $logsCreator;
    private $matchedRoute;
    private $urlRequest;
    private $methodRequest;

    public function __construct(ICollection $routesCollection, ICollection $globalVarsCollection, ICreator $logsCreator)
    {
        $this->routesCollection = $routesCollection;
        $this->globalVarsCollection = $globalVarsCollection;
        $this->logsCreator = $logsCreator;
    }

    /**
     * Start router. Call matching and extracting methods.
     * @param string $urlRequest
     * @param string $methodRequest
     */
    public function run(string $urlRequest, string $methodRequest)
    {
        $this->urlRequest = $urlRequest;
        $this->methodRequest = $methodRequest;

        $this->matchRoute();
        $this->extractRequestData();
    }

    public function getMatchedRoute(): IRoute
    {
        return $this->matchedRoute;
    }

    /**
     * Match the route up. If any route could not be match, call route '404 page' and find most similar route to given URL.
     */
    private function matchRoute(): void
    {
        $urlRequest = $this->urlRequest;
        $methodRequest = $this->methodRequest;

        try {
            $routeMatcher = new RouteMatching($this->routesCollection->getCollection());
            $this->matchedRoute = $routeMatcher->matchRouteByUrl($urlRequest, $methodRequest);

        } catch (CollectionException $exception) {

            $this->logsCreator->create('Error')->saveMessage('Route Matching Module', $exception);

        } catch (NotFoundException $exception) {
            try {
                $mostSimilarRoute = $routeMatcher->getMostSimilarRoute($urlRequest);
                $this->globalVarsCollection->addItem($mostSimilarRoute, 'similarRoute');

                $this->matchedRoute = $routeMatcher->matchRouteByName('404 page');

            } catch (NotFoundException $exception) {

                $this->logsCreator->create('Error')->saveMessage('Route Matching Module', $exception);
            }
        }
    }

    /**
     * Extract data from URL(filter and route-regex) and from input stream(like in PUT/DELETE/POST request)
     */
    private function extractRequestData()
    {
        $dataRender = new DataRendering();

        $fromUrl = $dataRender->renderFromUrl($this->urlRequest, $this->matchedRoute);
        $fromStream = $dataRender->renderFromInputStream();

        $dataRendered = array_merge($fromUrl, $fromStream);

        $this->globalVarsCollection->addItem($dataRendered, 'dataRequest');
    }
}