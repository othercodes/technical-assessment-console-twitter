<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application\UserSubscriber;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Exceptions\InvalidSubscribeToException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscribedId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Application\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

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
     * @param string $username
     * @param string $follow
     * @return int
     * @throws UserNotFoundException
     */
    public function execute(string $username, string $follow): int
    {
        try {

            $userFinder = new UserFinder($this->users);
            $userSubscriber = new UserSubscriber($this->subscriptions);

            $follower = $userFinder->byName(new UserName($username));
            $toFollow = $userFinder->byName(new UserName($follow));

            $subscribed = $userSubscriber->subscribe(
                new SubscriberId($follower->id()->value()),
                new SubscribedId($toFollow->id()->value())
            );

            $this->write(sprintf($subscribed ? Follow::SUBSCRIBED_SUCCESSFULLY : Follow::ALREADY_SUBSCRIBED,
                $follower->name(), $toFollow->name()
            ));

        } catch (InvalidSubscribeToException $e) {

            $this->write("{$e->getMessage()}\n");
        }

        return 0;
    }
}