<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Users;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

/**
 * Class UsersModuleUnitTestCase
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Users\Domain
 */
abstract class UsersModuleUnitTestCase extends MockeryTestCase
{
    /**
     * Mocked repository.
     * @var UserRepository
     */
    private $repository;

    /**
     * @param User $user
     */
    protected function shouldSave(User $user): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->with($user)
            ->once()
            ->andReturnNull();
    }

    /**
     * @param UserId $id
     * @param User|null $user
     */
    protected function shouldFind(UserId $id, ?User $user): void
    {
        $this->repository()
            ->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($user);
    }

    /**
     * @return UserRepository|MockInterface
     */
    protected function repository(): MockInterface
    {
        return $this->repository = $this->repository ?: Mockery::mock(UserRepository::class);
    }
}
