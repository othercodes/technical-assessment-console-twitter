<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Connector;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;

/**
 * Class Command
 * @property Connector $db
 * @property UserRepository $users
 * @property MessagesRepository $messages
 * @property SubscriptionsRepository $subscriptions
 * @package Lookiero\Hiring\ConsoleTwitter\Console
 */
abstract class Command
{
    /**
     * Service Container
     * @var Container
     */
    protected $container;

    /**
     * Command constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
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