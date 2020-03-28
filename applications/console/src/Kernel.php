<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console;

use Error;
use Exception;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Input as InputContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Kernel as KernelContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Output as OutputContract;


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
     * @var ContainerContract
     */
    protected $container;

    /**
     * Kernel constructor.
     * @param ContainerContract $container
     */
    public function __construct(ContainerContract $container)
    {
        $this->bootstrap($container);
    }

    /**
     * Bootstrap the kernel.
     * @param ContainerContract $container
     * @return KernelContract
     */
    public function bootstrap(ContainerContract $container): KernelContract
    {
        $this->container = $container;

        /** @var ContainerContract $configuration */
        $configuration = $this->container->get('configuration');

        foreach ($configuration->get('cli.commands', []) as $id => $command) {
            $this->container["cli.command.{$id}"] = function (ContainerContract $container) use ($command) {
                return new $command($container);
            };
        }

        $this->container->get('service.db');

        return $this;
    }

    /**
     * Handle the incoming input.
     * @param InputContract $input
     * @param OutputContract $output
     * @return mixed
     */
    public function handle(InputContract $input, OutputContract $output): int
    {
        try {

            /**
             * unpack the input arguments, and check if command exists in the
             * container, also check the offset 0 of the args contains something,
             * as this is the 1st argument (username) of all commands it is required.
             */
            ['ctrl' => $ctrl, 'args' => $args] = $input->getArguments();

            if (!$this->container->has("cli.command.{$ctrl}")) {
                throw new Exception("Command not '{$ctrl}' found.", 2);
            }

            $status = 0;
            if (!empty($args[0])) {
                $status = call_user_func_array([$this->container->get("cli.command.{$ctrl}"), 'execute'], $args);
            }

        } catch (Error | Exception $e) {

            $output->write("{$e->getMessage()}\n");

            return empty($e->getCode()) ? $e->getCode() : 1;
        }

        return $status;
    }
}