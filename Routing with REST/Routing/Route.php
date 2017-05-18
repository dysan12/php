<?php

namespace src\Routing;

/**
 * Class Route responsible for defying route instances.
 * @package Webqms\Routing
 */
class Route implements IRoute
{
    private $name;
    private $url;
    private $requestMethod;
    private $controllerSet = [];
    private $type;

    /**
     * Route constructor.
     * @param string $routeName - text route name
     * @param string $routeUrl - related URL, pattern inside allowed
     * @param array $routeSettings [] - other settings of the route
     * @param array $routeSettings ['requestMethod] - other settings of the route. Allowed keys: [string 'requestMethod',
     * array 'controllerSet' - array consist of [name_controller, method_controller], string type - collection/resource/site
     */
    public function __construct(string $routeName, string $routeUrl, array $routeSettings)
    {
        $this->name = $routeName;
        $this->url = strtolower($routeUrl);

        $this->requestMethod = $routeSettings['requestMethod'] ?? 'GET';
        $this->controllerSet['name'] = $routeSettings['controllerSet'][0] ?? null;
        $this->controllerSet['method'] = $routeSettings['controllerSet'][1] ?? null;
        $this->type = $routeSettings['type'] ?? 'site';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setRequestMethod(string $requestMethod): self
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }

    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    public function setControllerSet(array $controllerSet): self
    {
        $this->controllerSet = $controllerSet;

        return $this;
    }

    public function getControllerSet(): array
    {
        return $this->controllerSet;
    }

    public function setControllerName(string $controllerName): self
    {
        $this->controllerSet[0] = $controllerName;

        return $this;
    }

    public function getControllerName(): string
    {
        return $this->controllerSet[0] ?? NULL;
    }

    public function setControllerMethod(string $controllerMethod): self
    {
        $this->controllerSet[1] = $controllerMethod;

        return $this;
    }

    public function getControllerMethod(): string
    {
        return $this->controllerSet[1] ?? NULL;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Check if type of route is collection, if so query filter is enabled.
     * @return bool
     */
    public function isFilterEnabled(): bool
    {
        if ($this->getType() === 'collection') {
            return true;
        } else {
            return false;
        }
    }

}