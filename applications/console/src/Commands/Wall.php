<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Formatter;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\WallReader;

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
     * @throws Exception
     */
    public function execute(string $username): int
    {
        try {
            $reader = new WallReader($this->users, $this->messages, $this->subscriptions, new Formatter());
            $messages = $reader->readFrom($username);

            foreach ($messages as $message) {
                $this->write("$message\n");
            }

        } catch (Exception $e) {
            $this->write("{$e->getMessage()}\n");
        }

        return 0;
    }
}
