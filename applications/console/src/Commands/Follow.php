<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Subscriber;

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
    const SUBSCRIBED_SUCCESSFULLY = "%s has been subscribed to %s's feed.\n";
    const ALREADY_SUBSCRIBED = "%s is already subscribed to %s's feed.\n";

    /**
     * Handle the command execution.
     * @param string $follower
     * @param string $toFollow
     * @return int
     */
    public function execute(string $follower, string $toFollow): int
    {
        try {

            $subscriber = new Subscriber($this->users, $this->subscriptions);

            $this->write(sprintf($subscriber->subscribe($follower, $toFollow)
                ? Follow::SUBSCRIBED_SUCCESSFULLY
                : Follow::ALREADY_SUBSCRIBED,
                $follower, $toFollow
            ));

        } catch (Exception $e) {

            $this->write("{$e->getMessage()}\n");
        }

        return 0;
    }
}