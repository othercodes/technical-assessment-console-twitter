<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts;

/**
 * Interface Input
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Contracts
 */
interface Input
{
    /**
     * Read data from the input stream.
     * @return mixed
     */
    public function read();
}