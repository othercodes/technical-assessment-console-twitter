<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts;

/**
 * Interface Container
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts
 */
interface Container
{

    /**
     * True if the identifier exists, false if not.
     * @param mixed $id
     * @return bool
     */
    public function has($id): bool;

    /**
     * @param string $id
     * @param mixed $default
     * @return mixed
     */
    public function get($id, $default = null);

    /**
     * @param string $id
     * @param mixed $value
     * @return mixed
     */
    public function set($id, $value);

    /**
     * Remove a value by id.
     * @param mixed $id
     * @return $this
     */
    public function remove($id): Container;
}