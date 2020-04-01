<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application;

use Exception;
use Generator;
use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Contracts\Formatter;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application\SubscriptionFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Subscription;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class WallReader
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application
 */
final class WallReader
{
    /**
     * User Finder (Domain Service)
     * @var UserFinder
     */
    private $userFinder;

    /**
     * Message Finder (Domain Service)
     * @var MessageFinder
     */
    private $messageFinder;

    /**
     * Subscription Finder (Domain Service)
     * @var SubscriptionFinder
     */
    private $subscriptionFinder;

    /**
     * Message Formatter
     * @var Formatter
     */
    private $formatter;

    /**
     * WallReader constructor.
     * @param UserRepository $users
     * @param MessagesRepository $messages
     * @param SubscriptionsRepository $subscriptions
     * @param Formatter $formatter
     */
    public function __construct(
        UserRepository $users,
        MessagesRepository $messages,
        SubscriptionsRepository $subscriptions,
        Formatter $formatter
    ) {
        $this->userFinder = new UserFinder($users);
        $this->messageFinder = new MessageFinder($messages);
        $this->subscriptionFinder = new SubscriptionFinder($subscriptions);

        $this->formatter = $formatter;
    }

    /**
     * @param string $username
     * @return Generator
     * @throws Exception
     * @throws UserNotFoundException
     */
    public function readFrom(string $username): Generator
    {
        $user = $this->userFinder->byName(new UserName($username));

        $ownerIds = new Collection([new MessageOwnerId($user->id())]);
        $ownerIds = $ownerIds->merge($this->subscriptionFinder->bySubscriber(new SubscriberId($user->id()))
            ->map(function (Subscription $subscription) {
                return new MessageOwnerId($subscription->subscribed());
            }));

        $messageOwners = $ownerIds->map(function (MessageOwnerId $id) {
            return $this->userFinder->byId(new UserId($id));
        });

        /** @var Message $message */
        foreach ($this->messageFinder->byOwner(...$ownerIds->getValues()) as $message) {

            /** @var User $owner */
            $owner = $messageOwners->filter(function (User $user) use ($message) {
                return $message->owner()->equals($user->id());
            })->first();

            yield $this->formatter->format('{user} - {message} ({time.ago})', [
                'user' => $owner->name(),
                'message' => $message->text(),
                'time.ago' => $message->created()->asCreatedAgo()
            ]);
        }
    }
}
