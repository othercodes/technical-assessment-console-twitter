<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Formatter;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\TimelineReader;

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
        try {
            $reader = new TimelineReader($this->users, $this->messages, new Formatter());
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
