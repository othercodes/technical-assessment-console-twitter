<?php

namespace Lookiero\Hiring\ConsoleTwitter\Console;

use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Connector as ConnectorContract;
use Lookiero\Hiring\ConsoleTwitter\Repositories\UserRepository;

/**
 * Class Command
 * @property ConnectorContract $db
 * @property UserRepository $users
 * @package Lookiero\Hiring\ConsoleTwitter\Console
 */
abstract class Command
{

    /**
     * Service Container
     * @var ContainerContract
     */
    protected $container;

    /**
     * Command constructor.
     * @param ContainerContract $container
     */
    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    /**
     * Get any item from the container.
     * @param string $id
     * @return mixed|null
     */
    public function get(string $id)
    {
        return $this->container->has($id)
            ? $this->container->get($id)
            : null;
    }

    /**
     * Get the required service from the service container... (magically),
     * this getter will be used as services getter.
     * @param string $id
     * @return mixed|null
     */
    public function __get(string $id)
    {
        return $this->container->has('service.' . $id)
            ? $this->container->get('service.' . $id)
            : null;
    }

    /**
     * Write a message into the output.
     * @param string $message
     * @return void
     */
    public function write(string $message)
    {
        $this->container->get('io.output')->write($message);
    }
}