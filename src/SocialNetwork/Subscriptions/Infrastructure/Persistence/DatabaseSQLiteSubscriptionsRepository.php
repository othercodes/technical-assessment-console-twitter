<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Infrastructure\Persistence;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Connector;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Query;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscribedId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriberId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Subscription;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriptionId;

/**
 * Class DatabaseSQLiteSubscriptionsRepository
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Infrastructure\Persistence
 */
class DatabaseSQLiteSubscriptionsRepository implements SubscriptionsRepository
{
    /**
     * The Database connector instance.
     * @var Connector
     */
    private $connector;

    /**
     * DatabaseSQLiteSubscriptionsRepository constructor.
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Find a Subscription by SubscriptionId.
     * @param SubscriptionId $id
     * @return Subscription|null
     */
    public function find(SubscriptionId $id): ?Subscription
    {
        return $this->search([
            ['field' => 'id', 'operator' => '=', 'value' => quote($id->value())]
        ])->first();
    }

    /**
     * Search Subscriptions by the given array of criteria.
     * @param array $criteria
     * @return Collection|Subscription
     */
    public function search(array $criteria = []): Collection
    {
        try {
            $query = new Query();
            $query->select(['*'])->from(['subscriptions']);

            foreach ($criteria as $filter) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }

            return (new Collection($this->connector->execute($query)))
                ->map(function (array $item) {
                    return new Subscription(
                        new SubscriptionId($item['id']),
                        new SubscriberId($item['subscriber_id']),
                        new SubscribedId($item['subscribed_id'])
                    );
                });
        } catch (\Exception $e) {
            // add some log to control fail
            return new Collection();
        }
    }

    /**
     * Persists the given Subscription.
     * @param Subscription $subscription
     */
    public function save(Subscription $subscription): void
    {
        try {
            $query = new Query();
            $query->insert('subscriptions', [
                'id' => quote($subscription->id()->value()),
                'subscriber_id' => quote($subscription->subscriber()->value()),
                'subscribed_id' => quote($subscription->subscribed()->value())
            ]);

            $this->connector->execute($query);
        } catch (\Exception $e) {
            // add some log to control fail
        }
    }

    /**
     * Delete the given Subscription by Id.
     * @param SubscriptionId $id
     */
    public function delete(SubscriptionId $id): void
    {
        try {
            $query = new Query();
            $query->delete()
                ->from('subscriptions')
                ->where('id', '=', quote($id->value()));

            $this->connector->execute($query);
        } catch (\Exception $e) {
            // add some log to control fail
        }
    }
}
