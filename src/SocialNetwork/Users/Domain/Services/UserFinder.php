<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class UserFinder
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services
 */
final class UserFinder
{
    /**
     * The user repository implementation.
     * @var UserRepository
     */
    private $repository;

    /**
     * UserFinder constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find a user by Id.
     * @param UserId $id
     * @return User
     * @throws UserNotFoundException
     */
    public function byId(UserId $id): User
    {
        $user = $this->repository->find($id);

        if (is_null($user)) {
            throw new UserNotFoundException("User with id '{$id->value()}' not found.");
        }

        return $user;
    }

    /**
     * Find a user by name.
     * @param UserName $name
     * @return User
     * @throws UserNotFoundException
     */
    public function byName(UserName $name): User
    {
        $user = $this->repository->search([
            ['field' => 'name', 'operator' => '=', 'value' => quote($name->value())]
        ])->first();

        if (is_null($user)) {
            throw new UserNotFoundException("User '{$name->value()}' not found.");
        }

        return $user;
    }
}
