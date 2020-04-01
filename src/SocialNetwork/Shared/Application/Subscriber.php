<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application\SubscriptionCreator;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application\SubscriptionFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Exceptions\InvalidSubscribeToException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscribedId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriptionId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class UserSubscriber
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application
 */
final class Subscriber
{
    /**
     * User Finder (Domain Service).
     * @var UserFinder
     */
    private $userFinder;

    /**
     * Subscription Finder (Domain Service).
     * @var SubscriptionFinder
     */
    private $subscriptionFinder;

    /**
     * Subscription Creator (Domain Service).
     * @var SubscriptionCreator
     */
    private $subscriptionCreator;

    /**
     * Subscriber constructor.
     * @param UserRepository $users
     * @param SubscriptionsRepository $subscriptions
     */
    public function __construct(UserRepository $users, SubscriptionsRepository $subscriptions)
    {
        $this->userFinder = new UserFinder($users);

        $this->subscriptionFinder = new SubscriptionFinder($subscriptions);
        $this->subscriptionCreator = new SubscriptionCreator($subscriptions);
    }

    /**
     * Subscribe a user to another user feed.
     *  true if the subscription has been created.
     *  false if the subscription already exists.
     * @param string $subscriber
     * @param string $subscribed
     * @return bool
     * @throws InvalidSubscribeToException
     * @throws UserNotFoundException
     */
    public function subscribe(string $subscriber, string $subscribed): bool
    {
        $subscriber = $this->userFinder->byName(new UserName($subscriber));
        $subscribed = $this->userFinder->byName(new UserName($subscribed));

        if ($subscriber->id()->equals($subscribed->id())) {
            throw new InvalidSubscribeToException("A user cannot subscribe to himself.");
        }

        $subscriberId = new SubscriberId($subscriber->id());
        $subscribedId = new SubscribedId($subscribed->id());

        $subscription = $this->subscriptionFinder->bySubscriber($subscriberId, $subscribedId)->first();
        if (is_null($subscription)) {
            $this->subscriptionCreator->create(new SubscriptionId(uuid()), $subscriberId, $subscribedId);

            return true;
        }

        return false;
    }
}
