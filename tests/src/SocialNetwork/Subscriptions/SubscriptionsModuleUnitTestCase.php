<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Subscriptions\Domain;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Contracts\SubscriptionsRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\Subscription;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Subscriptions\Domain\SubscriptionId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

/**
 * Class SubscriptionsModuleUnitTestCase
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Subscriptions\Domain
 */
abstract class SubscriptionsModuleUnitTestCase extends MockeryTestCase
{
    /**
     * Mocked repository.
     * @var SubscriptionsRepository
     */
    private $repository;

    /**
     * @param Subscription $subscription
     */
    protected function shouldSave(Subscription $subscription): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->with($subscription)
            ->once()
            ->andReturnNull();
    }

    /**
     * @param SubscriptionId $id
     * @param Subscription|null $subscription
     */
    protected function shouldSearch(SubscriptionId $id, ?Subscription $subscription): void
    {
        $this->repository()
            ->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($subscription);
    }

    /**
     * @return SubscriptionsRepository|MockInterface
     */
    protected function repository(): MockInterface
    {
        return $this->repository = $this->repository ?: Mockery::mock(SubscriptionsRepository::class);
    }
}
