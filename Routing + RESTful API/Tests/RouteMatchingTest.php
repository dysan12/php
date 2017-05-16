<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RouteMatchingTest extends TestCase
{
    private $routeMatching;

    /**
     * Added 3 available types of routes resource/collection/site.
     */
    public function setUp()
    {
        $collection = new \Webqms\Routing\RoutesCollection();

        $collection->addItem(new \Webqms\Routing\Route('getUser', '/users/[\w%-]{1,30}', [
            'type' => 'resource',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('updateUser', '/users/[\w%-]{1,30}', [
            'type' => 'resource',
            'requestMethod' => 'PUT'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('deleteUser', '/users/[\w%-]{1,30}', [
            'type' => 'resource',
            'requestMethod' => 'DELETE'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('newUser', '/users', [
            'type' => 'collection',
            'requestMethod' => 'POST'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('getUsersCollection', '/users', [
            'type' => 'collection',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('deleteUsersCollection', '/users', [
            'type' => 'collection',
            'requestMethod' => 'DELETE'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('aboutMe', '/aboutMe/?', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('pageNotFound', '/404/?', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new \Webqms\Routing\Route('index', '', [
            'type' => 'site',
            'requestMethod' => 'GET'
        ]));

        $this->routeMatching = new \Webqms\Routing\RouteMatching($collection->getCollection());
    }
    /**
     * @dataProvider arrayWithCustomUrls
     */
    public function testIf_MatchRoute_CorrectlyReturnsRoutes(string $testingName, string $testingUrl, string $testingMethod)
    {
        $this->assertEquals($testingName ,$this->routeMatching->matchRouteByUrl($testingUrl, $testingMethod)->getName());
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

}
//<?php declare(strict_types=1);
//
//use PHPUnit\Framework\TestCase;
//
//final class RouterTest extends TestCase
//{
//
//    public function setUp()
//    {
//        /*
//         * $mockCollection = $this->createMock(Webqms\Routing\RoutesCollection::class);
//
//
//        $mock = $this->createMock(Webqms\Routing\Router::class);
//        $mock->expects($this->once())
//            ->method('getRequestUrl')
//            ->willReturn('/project/');
//
//        $this->object = new Webqms\Routing\Router($mock);
//        */
//    }
//
//    /**
//     * @dataProvider arrayWithGetValues
//     */
//    public function testCheckIfPrepareValuesFromUrlReturnsAllHandedValuesInUrl($returnedArray, $url, $routeArgNameAndPattern)
//    {
//        $objectMock = $this->getMockBuilder(Webqms\Routing\Router::class)
//            ->setMethods(['__construct'])
//            ->disableOriginalConstructor()
//            ->getMock();
//        $this->url = '/project/';
//
//        $routeMock = $this->createMock(Webqms\Routing\Route::class);
//        $routeMock->expects($this->once())
//            ->method('getArgument')
//            ->willReturn($routeArgNameAndPattern);
//
//        $objectMock->route = $routeMock;
//
//        $this->assertEquals(1, $returnedArray == $objectMock->prepareValuesFromUrl($url));
//    }
//
//    public function arrayWithGetValues()
//    {
//        return [
//            [
//                [
//                    'login' => 'dysan',
//                    'haslo' => 'kek'
//                ], '/project/?login=dysan&haslo=kek',
//                []
//            ],
//            [
//                [
//                    'name' => 'image.png',
//                    'id' => '1234'
//                ], '/project/getImage/flags/image.png?id=1234',
//                ['name', '/[a-zA-Z0-9.-]{0,30}/']
//            ],
//            [
//                [
//                    'id' => 'dysan'
//                ], '/project/user/get/dysan',
//                ['id', '/[a-zA-Z0-9.-]{0,30}/']
//            ],
//            [
//                [
//                    'id' => 'dysan'
//                ], '/project/user/get/dysan/',
//                ['id', '/[a-zA-Z0-9.-]{0,30}/']
//            ]
//        ];
//    }
//
//}