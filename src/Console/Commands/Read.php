<?php

namespace Lookiero\Hiring\ConsoleTwitter\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\Models\Message;

/**
 * Class Read
 *
 * Reading: Bob can view Alice’s timeline
 *  > Alice
 *  I love the weather today (5 minutes ago)
 *  > Bob
 *  Good game though. (1 minute ago)
 *  Damn! We lost! (2 minutes ago)
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Commands
 */
class Read extends Command
{
    /**
     * Handle the command execution.
     * @param string $username
     * @return int
     */
    public function execute(string $username): int
    {
        /** @var Message $message */
        foreach ($this->users->getUser($username)->messages() as $message) {
            $this->write("{$message->toString(false)}\n");
        }

        return 0;
    }
}