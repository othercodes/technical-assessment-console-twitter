<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;

/**
 * Class Quit
 * @package Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands
 */
class Quit extends Command
{
    /**
     * Handle the Quit command
     * @return int
     */
    public function execute(): int
    {
        return 255;
    }
}
