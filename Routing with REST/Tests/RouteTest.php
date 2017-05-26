<?php

class RouteTest extends PHPUnit\Framework\TestCase
{
    public function testFilterAvailability_WithVarietyRoutesTypes_ReturnsCorrectly()
    {
        $route = new App\Routing\Route('TestingRoute', 'test/world', ['type' => 'collection']);

        $this->assertEquals(1, $route->isFilterEnabled());

        $route->setType('resource');
        $this->assertEquals(0, $route->isFilterEnabled());
    }

}