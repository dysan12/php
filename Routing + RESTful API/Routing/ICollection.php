<?php

namespace Webqms\Routing;

interface ICollection
{
    public function addItem(IRoute $route);

    public function getCollection(): array;

}