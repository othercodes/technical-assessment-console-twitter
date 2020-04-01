<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscribedId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;

/**
 * Class SubscriptionFinder
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application
 */
final class SubscriptionFinder
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
     * Find subscriptions by subscriber id, additionally we can pass a
     * subscribed id to find that specific subscription.
     * @param SubscriberId $subscriber
     * @param SubscribedId|null $subscribed
     * @return Collection
     */
    public function bySubscriber(SubscriberId $subscriber, ?SubscribedId $subscribed = null): Collection
    {
        $criteria = [
            ['field' => 'subscriber_id', 'operator' => '=', 'value' => quote($subscriber)]
        ];

        if (isset($subscribed)) {
            $criteria[] = ['field' => 'subscribed_id', 'operator' => '=', 'value' => quote($subscribed)];
        }

        return $this->repository->search($criteria);
    }
}
