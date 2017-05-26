<?php

namespace App\Routing;


interface ICollection
{
    /**
     * Set collection to instance property
     * @param $collection
     * @return mixed
     */
    public function setCollection($collection): void;

    /**
     * Adding item to collection(if collection is not loaded method creates its ones)
     * @param $value - value of item
     * @param $index - name of item
     * @return mixed
     */
    public function addItem($value, $index): void;

    /**
     * Get item from collection, if item does not exist throw ItemCollectionException
     * @param $index
     * @return mixed
     */
    public function getItem($index);

    /**
     * @return array - handled collection
     */
    public function getCollection(): array;
}