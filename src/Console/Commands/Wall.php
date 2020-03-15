<?php

namespace Lookiero\Hiring\ConsoleTwitter\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\Models\Message;

/**
 * Class Wall
 *
 * Wall: view an aggregated list of all subscriptions.
 *  > Charlie wall
 *  Charlie - I'm in New York today! Anyone wants to have a coffee? (15 seconds ago)
 *  Bob - Good game though. (1 minute ago)
 *  Bob - Damn! We lost! (2 minutes ago)
 *  Alice - I love the weather today (5 minutes ago)
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Commands
 */
class Wall extends Command
{
    /**
     * Handle the command execution.
     * @param string $username
     * @return int
     */
    public function execute(string $username): int
    {
        /** @var Message $message */
        foreach ($this->users->getUser($username)->messages(true) as $message) {
            $this->write("{$message->toString(true)}\n");
        }

        return 0;
    }
}