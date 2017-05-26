<?php


class DataRenderingTest extends PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider arrayWithUrlsNVariables
     */
    public function testRenderingFromUrl_WithSpecificUrl_ReturnsAllIncludedData(string $url, array $result, App\Routing\IRoute $route)
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