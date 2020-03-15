<?php

namespace Lookiero\Hiring\ConsoleTwitter\Application;

use Closure;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Common\Collection;

/**
 * Class Container
 * @package Lookiero\Hiring\ConsoleTwitter\Application
 */
class Container extends Collection implements ContainerContract
{
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
     * Common getter.
     * @param mixed $id
     * @param null $default
     * @return mixed|null
     */
    public function get($id, $default = null)
    {
        if (!$this->has($id)) {
            return $default;
        }

        /**
         * Resolve deferred service and replace the deferred function with
         * the actual value of the it. If no deferred function, return directly
         * the value.
         */
        if ($this->values[$id] instanceof Closure || method_exists($this->values[$id], '__invoke')) {
            $this->values[$id] = $this->values[$id]($this);
        }

        return $this->values[$id];
    }

    /**
     * Common setter.
     * @param mixed $id
     * @param $value
     */
    public function set($id, $value)
    {
        $this->values[$id] = $value;
    }

    /**
     * Remove a value by id.
     * @param mixed $id
     * @return $this
     */
    public function remove($id): ContainerContract
    {
        unset($this->values[$id]);
        return $this;
    }

    /************************************************
     *              Magic Accessors
     ************************************************/

    /**
     * Get the required value by id.
     * @param string $id
     * @return mixed
     */
    public function __get($id)
    {
        return $this->get($id);
    }

    /**
     * Set the given pair id, value.
     * @param $id
     * @param $value
     */
    public function __set($id, $value)
    {
        $this->set($id, $value);
    }

    /**
     * Unset a value by id.
     * @param string $id
     */
    public function __unset($id)
    {
        $this->remove($id);
    }

    /**
     * Check a value presence by id.
     * @param string $id
     * @return boolean
     */
    public function __isset($id)
    {
        return $this->has($id);
    }

}