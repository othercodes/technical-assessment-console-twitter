<?php

namespace Lookiero\Hiring\ConsoleTwitter\Application;

use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;

/**
 * Class Provider
 * @package Lookiero\Hiring\ConsoleTwitter\Application
 */
abstract class Provider
{
    /**
     * Proxy method, run the class as a function.
     * @param ContainerContract $container
     */
    public function __invoke(ContainerContract $container)
    {
        return $this->load($container);
    }

    /**
     * Return the required service.
     * @param ContainerContract $container
     */
    abstract public function load(ContainerContract $container);
}