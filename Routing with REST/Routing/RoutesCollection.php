<?php

namespace App\Routing;

/**
 * Class RoutesCollection collects routes.
 * @package Webqms\Routing
 */
class RoutesCollection implements ICollection
{
    private $collection = [];

    public function addItem($route, $index = NULL)
    {
        if($index)
            $this->collection[$index] = $route;
        else
            $this->collection[] = $route;
    }

    public function getItem($index)
    {
        return $this->collection[$index];
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

}