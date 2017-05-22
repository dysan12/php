<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DataRenderingTest extends TestCase
{
    private $routeMatching;


    public function setUp()
    {
        $collection = new App\Routing\RoutesCollection();

        $collection->addItem(new App\Routing\Route('getUser', '/users/([\w%-]{1,30})', [
            'type' => 'resource',
            'requestMethod' => 'GET'
        ]));
        $collection->addItem(new App\Routing\Route('newUser', '/users', [
            'type' => 'collection',
            'requestMethod' => 'POST'
        ]));
    }
    /**
     * @dataProvider arrayWithUrlsNVariables
     */
    public function testIf_RenderURLDataReturnsAllData(string $url, array $result, App\Routing\IRoute $route)
    {
        $dataRender = new App\Routing\DataRendering();
        $this->assertEquals($result, $dataRender->renderFromUrl($url, $route));
    }

    public function arrayWithUrlsNVariables()
    {
        return [
            [
                '/users?order_by=name&set=3&chunk=15',
                ['order_by' => 'name', 'set' => '3', 'chunk' => '15', 'details' => []],
                new App\Routing\Route('user', '/users', ['type' => 'collection'])
            ],
            [
                '/users/dysan?order=name',
                ['details' => ['dysan']],
                new App\Routing\Route('getUser', '/users/([\w%-]{1,30})', ['type' => 'resource'])
            ]
        ];
    }
}