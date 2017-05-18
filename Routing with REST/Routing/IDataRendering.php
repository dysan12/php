<?php
namespace src\Routing;


interface IDataRendering
{
    public function renderFromUrl(string $url, IRoute $route): array;

    public function renderFromInputStream(): array;

}