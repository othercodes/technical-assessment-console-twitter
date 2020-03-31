<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Input as InputContract;
use Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts\Output as OutputContract;

/**
 * Interface Kernel
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts
 */
interface Kernel
{
    /**
     * Bootstrap the kernel.
     * @param Container $container
     * @return Kernel
     */
    public function bootstrap(Container $container): Kernel;

    /**
     * Handle the kernel execution.
     * @param InputContract $input
     * @param OutputContract $output
     * @return int
     */
    public function handle(InputContract $input, OutputContract $output): int;
}
