<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application\SubscriptionFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Subscription;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

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
            $userFinder = new UserFinder($this->users);
            $messageFinder = new MessageFinder($this->messages);
            $subscriptionFinder = new SubscriptionFinder($this->subscriptions);

            $user = $userFinder->byName(new UserName($username));

            $ownerIds = new Collection([new MessageOwnerId($user->id()->value())]);
            $ownerIds = $ownerIds->merge($subscriptionFinder->bySubscriber(new SubscriberId($user->id()->value()))
                ->map(function (Subscription $subscription) {
                    return new MessageOwnerId($subscription->subscribed()->value());
                }));

            $messageOwners = $ownerIds->map(function (MessageOwnerId $id) use ($userFinder) {
                return $userFinder->byId(new UserId($id->value()));
            });

            /** @var Message $message */
            foreach ($messageFinder->byOwner(...$ownerIds->getValues()) as $message) {

                /** @var User $owner */
                $owner = $messageOwners->filter(function (User $user) use ($message) {
                    return $message->owner()->equals($user->id());
                })->first();

                $this->write(sprintf(
                    "%s - %s (%s)\n",
                    $owner->name(),
                    $message->text(),
                    $message->created()->asCreatedAgo()
                ));
            }
        } catch (Exception $e) {
            $this->write("{$e->getMessage()}\n");
        }

        return 0;
    }
}
