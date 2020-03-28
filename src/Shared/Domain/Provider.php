<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Domain;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts\Container;

/**
 * Class Provider
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Domain
 */
abstract class Provider
{
    /**
     * Proxy method, run the class as a function.
     * @param Container $container
     */
    public function __invoke(Container $container)
    {
        return $this->load($container);
    }

    /**
     * Return the required service.
     * @param Container $container
     */
    abstract public function load(Container $container);
}