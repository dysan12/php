<?php

namespace Webqms\Routing;
/**
 * Class RoutesCollection collects routes.
 * @package Webqms\Routing
 */
class RoutesCollection implements ICollection
{
    private $collection = [];

    public function addItem(IRoute $route): void
    {
        $this->collection[] = $route;

    }

    public function getCollection(): array
    {
        return $this->collection;
    }

}