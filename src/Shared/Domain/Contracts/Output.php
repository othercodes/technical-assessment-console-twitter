<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts;

/**
 * Interface Output
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts
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