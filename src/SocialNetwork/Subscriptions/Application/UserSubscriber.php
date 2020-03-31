<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Exceptions\InvalidSubscribeToException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscribedId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriptionId;

/**
 * Class UserSubscriber
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Application
 */
final class UserSubscriber
{
    /**
     * Finder instance.
     * @var SubscriptionFinder
     */
    private $finder;

    /**
     * Creator instance
     * @var SubscriptionCreator
     */
    private $creator;

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
        $this->finder = new SubscriptionFinder($repository);
        $this->creator = new SubscriptionCreator($repository);

        $this->repository = $repository;
    }

    /**
     * Subscribe a user to another user feed.
     *  true if the subscription has been created.
     *  false if the subscription already exists.
     * @param SubscriberId $subscriberId
     * @param SubscribedId $subscribedId
     * @return bool
     * @throws InvalidSubscribeToException
     */
    public function subscribe(SubscriberId $subscriberId, SubscribedId $subscribedId): bool
    {
        if ($subscriberId->value() === $subscribedId->value()) {
            throw new InvalidSubscribeToException("A user cannot subscribe to himself.");
        }

        $subscription = $this->finder->bySubscriber($subscriberId, $subscribedId)->first();
        if (is_null($subscription)) {
            $this->creator->create(new SubscriptionId(uuid()), $subscriberId, $subscribedId);

            return true;
        }

        return false;
    }
}
