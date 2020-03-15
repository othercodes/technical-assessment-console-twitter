<?php

namespace Lookiero\Hiring\ConsoleTwitter\Application\Contracts;

/**
 * Interface Input
 * @package Lookiero\Hiring\ConsoleTwitter\Application
 */
interface Input
{
    /**
     * Read data from the input stream.
     * @return mixed
     */
    public function read();
}