<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;

/**
 * Interface UserRepository
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts
 */
interface UserRepository
{
    /**
     * Find a User by UserId.
     * @param UserId $id
     * @return User|null
     */
    public function find(UserId $id): ?User;

    /**
     * Search a User by the given array of criteria.
     * @param array $criteria
     * @return Collection|User
     */
    public function search(array $criteria = []): Collection;

    /**
     * Persists the given User.
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * Delete the given User by Id.
     * @param UserId $id
     */
    public function delete(UserId $id): void;
}
