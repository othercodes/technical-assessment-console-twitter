<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Provider;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Infrastructure\Persistence\DatabaseSQLiteSubscriptionsRepository;


/**
 * Class SubscriptionsRepositoryProvider
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers
 */
class SubscriptionsRepositoryProvider extends Provider
{
    /**
     * Return the required service.
     * @param ContainerContract $container
     * @return SubscriptionsRepository
     */
    public function load(ContainerContract $container)
    {
        return new DatabaseSQLiteSubscriptionsRepository($container->get('service.db'));
    }
}