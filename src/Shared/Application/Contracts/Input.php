<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts;

/**
 * Interface Input
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Application\Contracts
 */
interface Input
{
    /**
     * Read data from the input stream.
     * @return mixed
     */
    public function read();
}
