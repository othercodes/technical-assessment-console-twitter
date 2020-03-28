<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Repositories;

use InvalidArgumentException;
use Lookiero\Hiring\ConsoleTwitter\Models\User;

/**
 * Class UserRepository
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Repositories
 */
class UserRepository
{
    /**
     * Retrieve a User by username.
     * @param string $username
     * @return User
     */
    public function getUser(string $username)
    {
        /** @var User $user */
        $user = (new User())
            ->select(['*'])
            ->where('username', '=', $username, true)
            ->fetch()
            ->first();

        if (is_null($user)) {
            throw new InvalidArgumentException("User {$username} not found.", 10);
        }

        return $user;
    }
}