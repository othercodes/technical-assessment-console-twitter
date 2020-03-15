<?php

namespace Lookiero\Hiring\ConsoleTwitter\Application\Contracts;

/**
 * Interface Kernel
 * @package Lookiero\Hiring\ConsoleTwitter
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
     * @param Input $input
     * @param Output $output
     * @return int
     */
    public function handle(Input $input, Output $output): int;
}