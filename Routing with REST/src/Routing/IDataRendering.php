<?php
namespace App\Routing;


interface IDataRendering
{
    /**
     * Render data included in URL according to passed IRoute instance
     * @param string $url
     * @param IRoute $route
     * @return array - extracted data
     */
    public function renderFromUrl(string $url, IRoute $route): array;

    /**
     * @return array - extracted data from Input Stream
     */
    public function renderFromInputStream(): array;

}