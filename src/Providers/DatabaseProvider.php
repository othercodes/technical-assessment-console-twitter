<?php

namespace Lookiero\Hiring\ConsoleTwitter\Providers;

use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Connector as ConnectorContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Provider;
use Lookiero\Hiring\ConsoleTwitter\Database\Connector;
use Lookiero\Hiring\ConsoleTwitter\Database\ORM\Model;

/**
 * Class DatabaseProvider
 * @package Lookiero\Hiring\ConsoleTwitter\Providers
 */
class DatabaseProvider extends Provider
{
    /**
     * Return the required service.
     * @param ContainerContract $container
     * @return ConnectorContract
     */
    public function load(ContainerContract $container)
    {
        /** @var ContainerContract $configuration */
        $configuration = $container->get('configuration');

        /** Set the connector into the ORM model so any other model can access to it. */
        Model::setConnector($db = new Connector($configuration->get('database')));

        /**
         * Load the configuration and let the connection starts.
         * If something fails an exception will be raised... I dont
         * have any error control yet...
         */
        return $db;
    }
}