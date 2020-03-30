<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts;

/**
 * Interface Output
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts
 */
interface Output
{
    /**
     * Write data into the output stream.
     * @param string $data
     * @return bool
     */
    public function write(string $data): bool;
}