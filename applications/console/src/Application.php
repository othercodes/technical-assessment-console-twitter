<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Input;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Kernel;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Output;

/**
 * Class Application
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console
 */
class Application
{
    /**
     * Service Container
     * @var Container
     */
    protected $container;

    /**
     * Application constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Bind the given kernel.
     * @param string $kernel
     * @return Application
     */
    public function kernel($kernel): Application
    {
        $this->container->set("kernel", function (Container $container) use ($kernel) {
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
        $this->container->set('configuration', $configuration);

        if (isset($configuration['app.providers'])) {
            foreach ($configuration['app.providers'] as $id => $service) {
                $this->container["service.{$id}"] = new $service;
            }
        }

        return $this;
    }

    /**
     * Run the application...
     * @param Input $input
     * @param Output $output
     * @return int
     */
    public function run(Input $input, Output $output): int
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
         * @var Kernel $kernel
         */
        $kernel = $this->container->get("kernel");

        /**
         * let the kernel handle the arguments:
         *  > username [ -> message | follow username | wall ]
         */
        return $kernel->handle($input, $output);
    }
}
