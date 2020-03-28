<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers;

use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Provider;
use Lookiero\Hiring\ConsoleTwitter\Repositories\UserRepository;

/**
 * Class UserRepositoryProvider
 * @package Lookiero\Hiring\ConsoleTwitter\Providers
 */
class UserRepositoryProvider extends Provider
{
    /**
     * Return the required service.
     * @param ContainerContract $container
     * @return UserRepository
     */
    public function load(ContainerContract $container)
    {
        return new UserRepository();
    }
}