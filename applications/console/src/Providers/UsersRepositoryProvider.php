<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Provider;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Infrastructure\Persistence\DatabaseSQLiteUsersRepository;


/**
 * Class UsersRepositoryProvider
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers
 */
class UsersRepositoryProvider extends Provider
{
    /**
     * Return the required service.
     * @param ContainerContract $container
     * @return UserRepository
     */
    public function load(ContainerContract $container)
    {
        return new DatabaseSQLiteUsersRepository($container->get('service.db'));
    }
}