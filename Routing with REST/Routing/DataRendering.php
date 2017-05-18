<?php

namespace src\Routing;

/**
 * Class DataRendering responsible for rendering data from URL and input stream.
 * @package Webqms\Routing
 */
class DataRendering
{
    /**
     * Return data contained in URL.
     * @param string $url - requested URL.
     * @param IRoute $route - IRoute instance.
     * @return array - URL's data
     */
    public function renderFromUrl(string $url, IRoute $route): array
    {
        if ($route->isFilterEnabled()) {
            $data = $this->renderFilter($url);
        }
        $data['details'] = $this->renderByRegex($url, $route->getUrl());

        return $data;
    }

    /**
     * Take data from input stream(handling with data from POST, PUT, DELETE etc.)
     * @return array
     */
    public function renderFromInputStream(): array
    {
        return $this->renderFromString(file_get_contents("php://input"));
    }

    /**
     * @param $url - request's URL.
     * @param $routeUrl - url with regex.
     * @return array - return all data matched by regex contained in url.
     */
    private function renderByRegex($url, $routeUrl): array
    {
        $routeRegexUrl = '/' . addcslashes($routeUrl, '/') . '/';
        preg_match_all($routeRegexUrl, $url, $resources);

        return $resources[1] ?? [];
    }

    private function renderFilter(string $url): array
    {
        $chunks = explode('?', $url);
        if (count($chunks) === 2) {
            $data = $this->renderFromString($chunks[1]);
        }

        return $data ?? [];
    }

    /**
     * Render string into variable's array in convention name=value&name2=value2 etc.
     * @param string $string
     * @return array
     */
    private function renderFromString(string $string): array
    {
        parse_str($string, $data);

        return $data;
    }
}