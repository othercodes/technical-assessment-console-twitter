<?php

namespace Lookiero\Hiring\ConsoleTwitter\Common;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Lookiero\Hiring\ConsoleTwitter\Common\Contracts\Collection as CollectionContract;

/**
 * Class Collection
 * @package Lookiero\Hiring\ConsoleTwitter\Common
 */
class Collection implements CollectionContract, ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Collection values.
     * @var array
     */
    protected $values = [];

    /**
     * Container constructor.
     * @param array $source
     */
    public function __construct(array $source = [])
    {
        $this->hydrate($source);
    }

    /**
     * Hydrate the Collection.
     * @param array $values
     * @return static
     */
    public function hydrate($values = [])
    {
        foreach ($values as $id => $value) {
            $this->values[$id] = $value;
        }
        return $this;
    }

    /**
     * Return the raw items of the collection.
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Return the list of value ids.
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->values);
    }

    /**
     * Return the required value by id.
     * @param string $id
     * @param null $default
     * @return mixed
     */
    public function get($id, $default = null)
    {
        return $this->has($id) ? $this->values[$id] : $default;
    }

    /**
     * True if the identifier exists, false if not.
     * @param mixed $id
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->values[$id]);
    }

    /**
     * Get the first item of the collection.
     * @return mixed
     */
    public function first()
    {
        if (!empty($this->values)) {
            foreach ($this->values as $item) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Get the last item of the collection.
     * @return mixed
     */
    public function last()
    {
        if (!empty($this->values)) {
            foreach (array_reverse($this->values) as $item) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Map each item of the collection with a callback.
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $values = [];
        foreach ($this->values as $id => $value) {
            $values[$id] = $callback($value, $id);
        }

        return new static($values);
    }

    /**
     * Filter the values using a callback.
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback)
    {
        return new static(array_filter($this->values, $callback));
    }

    /**
     * Merge the current Collection with the given one.
     * @param CollectionContract $collection
     * @return CollectionContract
     */
    public function merge(CollectionContract $collection): CollectionContract
    {
        return new static(array_merge($this->getValues(), $collection->getValues()));
    }


    /************************************************
     *              Transformations
     ************************************************/

    /**
     * Transform the current model into array.
     * @return array
     */
    public function toArray(): array
    {
        return arrayize($this->values);
    }

    /**
     * Transform the current model into json.
     * @param int $options
     * @return string
     */
    public function toJSON($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /************************************************
     *          Countable Interface
     ************************************************/

    /**
     * Count the items of the collection.
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /************************************************
     *          ArrayAccess Interface
     ************************************************/

    /**
     * Return the requested entry from the values.
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    /**
     * Set a given entry in the values.
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
    }

    /**
     * Check if a entry exists or not in the values.
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * Unset an element from the values.
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /************************************************
     *         IteratorAggregate Interface
     ************************************************/

    /**
     * Return an array iterator for the model values.
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }
}