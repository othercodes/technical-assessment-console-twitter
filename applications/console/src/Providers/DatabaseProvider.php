<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Provider;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Connector;

/**
 * Class DatabaseProvider
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console\Providers
 */
class DatabaseProvider extends Provider
{
    /**
     * Return the required service.
     * @param ContainerContract $container
     * @return Connector
     */
    public function load(ContainerContract $container)
    {
        /** @var ContainerContract $configuration */
        $configuration = $container->get('configuration');

        /**
         * Load the configuration and let the connection starts.
         * If something fails an exception will be raised... I dont
         * have any error control yet...
         */
        return new Connector($configuration['database']);
    }
}
