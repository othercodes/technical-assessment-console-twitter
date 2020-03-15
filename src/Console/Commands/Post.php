<?php

namespace Lookiero\Hiring\ConsoleTwitter\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Console\Command;

/**
 * Class Post
 *
 * Posting: Alice can publish messages to a personal timeline.
 *  > Alice -> I love the weather today
 *  > Bob -> Damn! We lost!
 *  > Bob -> Good game though.
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Commands
 */
class Post extends Command
{
    /**
     * Handle the command execution.
     * @param string $username
     * @param string $message
     * @return int
     */
    public function execute(string $username, string $message): int
    {
        return ($this->users->getUser($username)->message($message)) ? 0 : 11;
    }
}