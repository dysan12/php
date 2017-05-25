<?php

namespace App\Routing;


interface ICollection
{
    public function addItem($value, $index);

    public function getItem($index);

    public function getCollection(): array;
}