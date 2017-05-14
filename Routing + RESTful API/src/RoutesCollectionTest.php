<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RoutesCollectionTest extends TestCase
{
    private $collection;

    /**
     * Added 3 available types of routes resource/collection/site.
     */
    public function setUp()
    {
        $collection = new \Webqms\Routing\RoutesCollection();

        $collection->addRoute(new \Webqms\Routing\Route('getUser', '/users/[\w%-]{1,30}', [
            'type' => 'resource',
            'requestMethod' => 'GET'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('updateUser', '/users/[\w%-]{1,30}', [
            'type' => 'resource',
            'requestMethod' => 'PUT'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('deleteUser', '/users/[\w%-]{1,30}', [
            'type' => 'resource',
            'requestMethod' => 'DELETE'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('newUser', '/users', [
            'type' => 'collection',
            'requestMethod' => 'POST'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('getUsersCollection', '/users', [
            'type' => 'collection',
            'requestMethod' => 'GET'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('deleteUsersCollection', '/users', [
            'type' => 'collection',
            'requestMethod' => 'DELETE'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('aboutMe', '/aboutMe/?', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));
        $collection->addRoute(new \Webqms\Routing\Route('index', '', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));

        $this->collection = $collection;
    }
    /**
     * @dataProvider arrayWithCustomUrls
     */
    public function testIf_MatchRoute_CorrectlyReturnsRoutes(string $testingName, string $testingUrl, string $testingMethod)
    {
        $this->assertEquals($testingName ,$this->collection->matchRoute($testingUrl, $testingMethod)->getName());
    }

    public function arrayWithCustomUrls()
    {
        return [
            ['getUser', '/users/dysan', 'GET'],
            ['deleteUser', '/users/123123', 'DELETE'],
            ['updateUser', '/users/michal%20jan', 'PUT'],
            ['newUser', '/users', 'POST'],
            ['deleteUsersCollection', '/users/', 'DELETE'],
            ['getUsersCollection', '/users?order_by=login&set=2&chunk=25', 'GET'],
            ['getUsersCollection', '/users?chunk=50', 'GET'],
            ['aboutMe', '/aboutme/', 'GET'],
            ['aboutMe', '/aboutme', 'GET'],
            ['index', '', 'GET']
        ];
    }

    /**
     * @dataProvider arrayWithValidNInvalidUrls
     */
    public function testIf_getMostSimilarRoute_ReturnSimilarRoute(string $validUrl, string $invalidUrl)
    {
        $similarRouteUrl = $this->collection->getMostSimilarRoute($invalidUrl)->getUrl();
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

}