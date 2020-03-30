<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain;

/**
 * Class Subscription (Aggregate)
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain
 */
final class Subscription
{
    /**
     * Subscription Id.
     * @var SubscriptionId
     */
    private $id;

    /**
     * Subscriber User Id.
     * @var SubscriberId
     */
    private $subscriber;

    /**
     * Subscribed Used Id.
     * @var SubscribedId
     */
    private $subscribed;

    /**
     * Subscription constructor.
     * @param SubscriptionId $id
     * @param SubscriberId $subscriber
     * @param SubscribedId $subscribed
     */
    public function __construct(SubscriptionId $id, SubscriberId $subscriber, SubscribedId $subscribed)
    {
        $this->id = $id;
        $this->subscriber = $subscriber;
        $this->subscribed = $subscribed;
    }

    /**
     * Return the subscription Id.
     * @return SubscriptionId
     */
    public function id(): SubscriptionId
    {
        return $this->id;
    }

    /**
     * Return the subscriber user Id.
     * @return SubscriberId
     */
    public function subscriber(): SubscriberId
    {
        return $this->subscriber;
    }

    /**
     * Return the subscribed-to user Id.
     * @return SubscribedId
     */
    public function subscribed(): SubscribedId
    {
        return $this->subscribed;
    }

}