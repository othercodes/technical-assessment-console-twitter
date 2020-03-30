<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Provider;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Infrastructure\Persistence\DatabaseSQLiteMessagesRepository;


/**
 * Class MessagesRepositoryProvider
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers
 */
class MessagesRepositoryProvider extends Provider
{
    /**
     * Return the required service.
     * @param ContainerContract $container
     * @return MessagesRepository
     */
    public function load(ContainerContract $container)
    {
        return new DatabaseSQLiteMessagesRepository($container->get('service.db'));
    }
}