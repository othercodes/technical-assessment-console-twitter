<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Subscription;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriptionId;

/**
 * Interface SubscriptionsRepository
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts
 */
interface SubscriptionsRepository
{
    /**
     * Find a Subscription by SubscriptionId.
     * @param SubscriptionId $id
     * @return Subscription|null
     */
    public function find(SubscriptionId $id): ?Subscription;

    /**
     * Search a Subscription by the given array of criteria.
     * @param array $criteria
     * @return mixed
     */
    public function search(array $criteria = []): Collection;

    /**
     * Persists the given Subscription.
     * @param Subscription $subscription
     */
    public function save(Subscription $subscription): void;
}
