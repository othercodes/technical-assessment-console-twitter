<?php

namespace Lookiero\Hiring\ConsoleTwitter\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Console\Command;

/**
 * Class Follow
 *
 * Following: Charlie can subscribe to Alice’s and Bob’s timelines
 *  > Charlie -> I'm in New York today! Anyone want to have a coffee?
 *  > Charlie follows Alice
 *  > Charlie follows Bob
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Commands
 */
class Follow extends Command
{
    /**
     * Handle the command execution.
     * @param string $username
     * @param string $follow
     * @return int
     */
    public function execute(string $username, string $follow): int
    {
        return ($this->users->getUser($username)->follow($this->users->getUser($follow))) ? 0 : 20;
    }

}