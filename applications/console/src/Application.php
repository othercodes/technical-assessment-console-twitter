<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console;

use InvalidArgumentException;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Input as InputContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Kernel as KernelContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Output as OutputContract;
use Lookiero\Hiring\ConsoleTwitter\Common\Collection;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Connector as ConnectorContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Query;
use PDOException;

/**
 * Class Application
 * @package Lookiero\Hiring\ConsoleTwitter
 */
class Application
{
    /**
     * Service Container
     * @var ContainerContract
     */
    protected $container;

    /**
     * Application constructor.
     * @param ContainerContract $container
     */
    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    /**
     * Return the container.
     * @return ContainerContract
     */
    public function getContainer(): ContainerContract
    {
        return $this->container;
    }

    /**
     * Bind the given kernel.
     * @param string $kernel
     * @return Application
     */
    public function kernel($kernel): Application
    {
        $this->container->set("kernel", function (ContainerContract $container) use ($kernel) {
            return new $kernel($container);
        });

        return $this;
    }

    /**
     * Load the configuration into the container.
     * @param array $configuration
     * @return Application
     */
    public function configure(array $configuration = []): Application
    {
        /**
         * Load the configuration into the container as a collection (container) to ease the
         * usage and initialize the service providers, each service is deferred until the
         * real usage.
         */
        $this->container->set('configuration', $configuration = new Collection($configuration));

        foreach ($configuration->get('app.providers', []) as $id => $service) {
            $this->container["service.{$id}"] = new $service;
        }

        return $this;
    }

    /**
     * Install the database application schema if is required.
     * @return bool
     */
    public function install(): bool
    {
        try {

            /** @var ConnectorContract $db */
            $db = $this->container->get('service.db');
            $db->execute((new Query())->select(['count(*)'])->from(['users']));

        } catch (PDOException $e) {

            /** @var array $configuration */
            $configuration = $this->container->get('configuration')->get('database');

            if (!is_readable($configuration['schema'])) {
                throw new InvalidArgumentException("Unable to read '{$configuration['schema']}' install file.");
            }

            $schemas = (new Collection(explode(';', file_get_contents($configuration['schema']))))
                ->map(function (string $statement) {
                    return trim($statement);
                })
                ->filter(function (string $statement) {
                    return !empty($statement);
                });

            foreach ($schemas as $schema) {
                $db->execute($schema);
            }
        }

        return true;
    }

    /**
     * Run the application...
     * @param InputContract $input
     * @param OutputContract $output
     * @return int
     */
    public function run(InputContract $input, OutputContract $output): int
    {
        /**
         * push the Input and Output into the container so they can be available
         * across the application...
         */
        $this->container->set('io.input', $input);
        $this->container->set('io.output', $output);

        /**
         * Load the required Kernel based on the current server api. For this use
         * case I only have the Console Kernel so if this is executed in web server
         * this will crash...
         * @var KernelContract $kernel
         */
        $kernel = $this->container->get("kernel");

        /**
         * let the kernel handle the arguments:
         *  > username [ -> message | follow username | wall ]
         */
        return $kernel->handle($input, $output);
    }

}