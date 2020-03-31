<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\TimelinePublisher;

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
        try {

            $publisher = new TimelinePublisher($this->users, $this->messages);
            $publisher->publish($username, $message);

        } catch (Exception $e) {

            $this->write("{$e->getMessage()}\n");
        }

        return 0;
    }
}