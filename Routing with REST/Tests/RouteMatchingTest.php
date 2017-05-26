<?php

use PHPUnit\Framework\TestCase;

final class RouteMatchingTest extends TestCase
{
    private $routeMatching;

    /**
     * Added 3 available types of routes resource/collection/site.
     */
    public function setUp()
    {
        $collection = new App\Routing\RoutesCollection();

        $collection->addItem(new App\Routing\Route('getUser', '/users/([\w%-]{1,30})', [
            'type' => 'resource',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new App\Routing\Route('updateUser', '/users/([\w%-]{1,30})', [
            'type' => 'resource',
            'requestMethod' => 'PUT'
        ]));
        $collection->addItem(new App\Routing\Route('deleteUser', '/users/([\w%-]{1,30})', [
            'type' => 'resource',
            'requestMethod' => 'DELETE'
        ]));
        $collection->addItem(new App\Routing\Route('newUser', '/users', [
            'type' => 'collection',
            'requestMethod' => 'POST'
        ]));
        $collection->addItem(new App\Routing\Route('getUsersCollection', '/users', [
            'type' => 'collection',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new App\Routing\Route('deleteUsersCollection', '/users', [
            'type' => 'collection',
            'requestMethod' => 'DELETE'
        ]));
        $collection->addItem(new App\Routing\Route('aboutMe', '/aboutMe/?', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new App\Routing\Route('pageNotFound', '/404/?', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new App\Routing\Route('index', '/?', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));

        $this->routeMatching = new App\Routing\RouteMatching($collection->getCollection());
    }
    /**
     * @dataProvider arrayWithCustomUrls
     */
    public function testMatchingRoutesByUrl_WithProperData_ReturnsCorrectRoutes(string $testingName, string $testingUrl, string $testingMethod)
    {
        $this->assertEquals($testingName ,$this->routeMatching->matchRouteByUrl($testingUrl, $testingMethod)->getName());
    }

    public function arrayWithCustomUrls()
    {
        return [
            ['getUser', '/users/dysan', 'GET'],
            ['deleteUser', '/users/dysan', 'DELETE'],
            ['updateUser', '/users/michal%20jan', 'PUT'],
            ['newUser', '/users', 'POST'],
            ['deleteUsersCollection', '/users/', 'DELETE'],
            ['getUsersCollection', '/users?order_by=login&set=2&chunk=25', 'GET'],
            ['getUsersCollection', '/users?chunk=50', 'GET'],
            ['aboutMe', '/aboutme/', 'GET'],
            ['aboutMe', '/aboutme', 'GET'],
            ['index', '/', 'GET']
        ];
    }

    /**
     * @dataProvider arrayWithValidNInvalidUrls
     */
    public function testGetSimilarRoute_WithDistortedUrl_ReturnsSimilarRoutes(string $validUrl, string $invalidUrl)
    {
        $similarRouteUrl = $this->routeMatching->getMostSimilarRoute($invalidUrl)->getUrl();
        $similarRouteUrl = addcslashes($similarRouteUrl, '/');
        $this->assertEquals(1, preg_match('/' . $similarRouteUrl . '/', $validUrl));
    }

    public function arrayWithValidNInvalidUrls()
    {
        return [
            ['/users/dysan', '/user/dysan'],
            ['/users/123123', '/urers/123123'],
            ['/users/michal%20jan', '/use/michal%20jan'],
            ['/users/123', '/userskjewqh/123'],
            ['/aboutme/', '/abome/'],
            ['/aboutme', '/aboutME']
        ];
    }

    public function testMatchingRouteByUrl_IfRouteWasntDefined_ThrowException()
    {
        $this->expectException(App\Exceptions\NotFoundException::class);

        $this->routeMatching->matchRouteByUrl('/useeeeer/login');
    }

    /**
     * @dataProvider arrayWithSetOfNames
     */
    public function testMatchingRouteByName_WithCorrectNames_ReturnsRoutes(string $testingName, string $testingUrl, string $testingMethod)
    {
        $this->assertEquals($testingName ,$this->routeMatching->matchRouteByName($testingUrl, $testingMethod)->getName());
    }

    public function arrayWithSetOfNames()
    {
        return [
            ['getUser', 'getUser', 'GET'],
            ['deleteUser', 'deleteUser', 'DELETE'],
            ['updateUser', 'updateUser', 'PUT']
        ];
    }

    public function testMatchingRouteByName_IfRouteWasntDefined_ThrowException()
    {
        $this->expectException(Currency\Exceptions\NotFoundException::class);

        $this->routeMatching->matchRouteByName('whatever');
    }
}