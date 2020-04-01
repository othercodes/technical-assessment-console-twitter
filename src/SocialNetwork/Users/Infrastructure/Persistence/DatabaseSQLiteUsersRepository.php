<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Infrastructure\Persistence;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Connector;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Query;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\User;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class DatabaseSQLiteUserRepository
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Infrastructure\Persistence
 */
class DatabaseSQLiteUsersRepository implements UserRepository
{
    /**
     * The Database connector instance.
     * @var Connector
     */
    private $connector;

    /**
     * DatabaseSQLiteUserRepository constructor.
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Find a User by UserId.
     * @param UserId $id
     * @return User|null
     */
    public function find(UserId $id): ?User
    {
        return $this->search([
            ['field' => 'id', 'operator' => '=', 'value' => quote($id)]
        ])->first();
    }

    /**
     * Search Users by the given array of criteria.
     * @param array $criteria
     * @return Collection|User
     */
    public function search(array $criteria = []): Collection
    {
        try {
            $query = new Query();
            $query->select(['*'])->from(['users']);

            foreach ($criteria as $filter) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }

            return (new Collection($this->connector->execute($query)))
                ->map(function (array $item) {
                    return new User(new UserId($item['id']), new UserName($item['name']));
                });
        } catch (\Exception $e) {
            // add some log to control fail
            return new Collection();
        }
    }

    /**
     * Persists the given User.
     * @param User $user
     */
    public function save(User $user): void
    {
        try {
            $query = new Query();
            $query->insert('users', [
                'id' => quote($user->id()),
                'name' => $user->name()
            ]);

            $this->connector->execute($query);
        } catch (\Exception $e) {
            // add some log to control fail
        }
    }

    /**
     * Delete the given User by Id.
     * @param UserId $id
     */
    public function delete(UserId $id): void
    {
        try {
            $query = new Query();
            $query->delete()
                ->from('users')
                ->where('id', '=', quote($id));

            $this->connector->execute($query);
        } catch (\Exception $e) {
            // add some log to control fail
        }
    }
}
