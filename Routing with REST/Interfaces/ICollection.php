<?php

namespace src\Interfaces;


interface ICollection
{
    public function addItem($value, $index);

    public function getItem($index);

    public function getCollection(): array;
}