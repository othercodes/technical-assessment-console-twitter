<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Users\Domain\Services;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;
use Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Users\UsersModuleUnitTestCase;
use Mockery;

/**
 * Class UserFinderTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\src\SocialNetwork\Users\Domain\Services
 */
class UserFinderTest extends UsersModuleUnitTestCase
{
    /**
     * @throws UserNotFoundException
     */
    public function testItShouldFindAnExistingUserById(): void
    {
        $user = new User($id = new UserId(uuid()), new UserName('Vincent'));

        $repository = Mockery::mock(UserRepository::class);
        $repository->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($user);

        $userFinder = new UserFinder($repository);
        $this->assertEquals($user, $userFinder->byId($id));
    }

    /**
     * @throws UserNotFoundException
     */
    public function testItShouldFindAnExistingUserByName(): void
    {
        $user = new User(new UserId(uuid()), $name = new UserName('Vincent'));

        $repository = Mockery::mock(UserRepository::class);
        $repository->shouldReceive('search')
            ->with([['field' => 'name', 'operator' => '=', 'value' => quote($name)]])
            ->once()
            ->andReturn(new Collection([$user]));

        $userFinder = new UserFinder($repository);
        $this->assertEquals($user, $userFinder->byName($name));
    }

    /**
     * @throws UserNotFoundException
     */
    public function testItShouldThrowAnExceptionWhenUserNotExistById(): void
    {
        $this->expectException(UserNotFoundException::class);

        $user = new User($id = new UserId(uuid()), new UserName('Vincent'));

        $repository = Mockery::mock(UserRepository::class);
        $repository->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturnNull();

        $userFinder = new UserFinder($repository);
        $userFinder->byId($id);
    }

    /**
     * @throws UserNotFoundException
     */
    public function testItShouldThrowAnExceptionWhenUserNotExistByName(): void
    {
        $this->expectException(UserNotFoundException::class);

        $user = new User(new UserId(uuid()), $name = new UserName('Vincent'));

        $repository = Mockery::mock(UserRepository::class);
        $repository->shouldReceive('search')
            ->with([['field' => 'name', 'operator' => '=', 'value' => quote($name)]])
            ->once()
            ->andReturn(new Collection());

        $userFinder = new UserFinder($repository);
        $userFinder->byName($name);
    }
}
