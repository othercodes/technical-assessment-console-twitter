<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscribedId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Subscription;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriptionId;

/**
 * Class SubscriptionCreator
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application
 */
final class SubscriptionCreator
{
    /**
     * The user repository implementation.
     * @var SubscriptionsRepository
     */
    private $repository;

    /**
     * UserSubscriber constructor.
     * @param SubscriptionsRepository $repository
     */
    public function __construct(SubscriptionsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a subscription.
     * @param SubscriptionId $id
     * @param SubscriberId $subscriber
     * @param SubscribedId $subscribed
     * @return Subscription
     */
    public function create(SubscriptionId $id, SubscriberId $subscriber, SubscribedId $subscribed)
    {
        $subscription = new Subscription($id, $subscriber, $subscribed);
        $this->repository->save($subscription);
        return $subscription;
    }
}
