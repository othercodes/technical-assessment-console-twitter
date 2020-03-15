<?php

namespace Lookiero\Hiring\ConsoleTwitter\Common\Contracts;

/**
 * Interface Collection
 * @package Lookiero\Hiring\ConsoleTwitter\Common\Contracts
 */
interface Collection
{
    /**
     * Return the raw items of the collection.
     * @return array
     */
    public function getValues(): array;

    /**
     * Return the list of value ids.
     * @return array
     */
    public function keys(): array;

    /**
     * Get a item by id.
     * @param string $id
     * @param mixed $default
     * @return mixed
     */
    public function get($id, $default = null);

    /**
     * Get the first item of the collection.
     * @return mixed
     */
    public function first();

    /**
     * Get the last item of the collection.
     * @return mixed
     */
    public function last();

    /**
     * Map each item of the collection with a callback.
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback);

    /**
     * Filter the values using a callback.
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback);

    /**
     * Merge the current Collection with the given one.
     * @param Collection $collection
     * @return static
     */
    public function merge(Collection $collection);

}