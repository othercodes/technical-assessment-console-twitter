<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console;

use Error;
use Exception;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Container;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Input;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Kernel as KernelContract;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Output;

/**
 * Class Kernel
 *
 * Error codes:
 *  1: Unexpected error.
 *  2: Command not found.
 *  10: Model not found.
 *  11: Unable to save the model into the database.
 *  20: Unable to follow the given user.
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console
 */
class Kernel implements KernelContract
{
    /**
     * Supported API for this kernel.
     * @var string
     */
    const SAPI = 'cli';

    /**
     * Service Container
     * @var Container
     */
    protected $container;

    /**
     * Kernel constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->bootstrap($container);
    }

    /**
     * Bootstrap the kernel.
     * @param Container $container
     * @return Kernel
     */
    public function bootstrap(Container $container): KernelContract
    {
        $this->container = $container;

        /** @var Container $configuration */
        $configuration = $this->container->get('configuration');

        if (isset($configuration['cli.commands'])) {
            foreach ($configuration['cli.commands'] as $id => $command) {
                $this->container["cli.command.{$id}"] = function (Container $container) use ($command) {
                    return new $command($container);
                };
            }
        }

        $this->container->get('service.db');

        return $this;
    }

    /**
     * Handle the incoming input.
     * @param Input $input
     * @param Output $output
     * @return mixed
     */
    public function handle(Input $input, Output $output): int
    {
        try {
            $status = 0;

            /**
             * unpack the input arguments, and check if command exists in the
             * container, also check the offset 0 of the args contains something,
             * as this is the 1st argument (username) of all commands it is required.
             */
            ['ctrl' => $ctrl, 'args' => $args] = $input->getArguments();

            if ($this->container->has("cli.command.{$ctrl}")) {
                if (!empty($args[0])) {
                    $status = call_user_func_array([$this->container->get("cli.command.{$ctrl}"), 'execute'], $args);
                }
            } else {
                $output->write("Command '{$ctrl}' not found.\n");
            }
        } catch (Error | Exception $e) {
            $output->write("{$e->getMessage()}\n");

            return empty($e->getCode()) ? $e->getCode() : 1;
        }

        return $status;
    }
}
