<?php

namespace Lookiero\Hiring\ConsoleTwitter\Models;

use Lookiero\Hiring\ConsoleTwitter\Common\Collection;
use Lookiero\Hiring\ConsoleTwitter\Database\ORM\Model;

/**
 * Class User
 * @property int $id
 * @property string $username
 * @property string created
 * @package Lookiero\Hiring\ConsoleTwitter\Models
 */
class User extends Model
{
    /**
     * List of attributes of the model
     * @var array
     */
    protected $attributes = [
        'id',
        'username',
        'created',
    ];

    /**
     * The table name.
     * @var string
     */
    protected $table = 'users';

    /**
     * Return the list of messages.
     * @param bool $following
     * @return Collection
     */
    public function messages($following = false): Collection
    {
        $users = new Collection([$this->get($this->getPrimaryKey())]);

        if ($following) {
            $users = $users->merge((new Following())
                ->select(['*'])
                ->where('follower_id', '=', $this->get($this->getPrimaryKey()))
                ->fetch()
                ->map(function (Following $following) {
                    return $following->user_id;
                }));
        }

        return (new Message())
            ->select(['*'])
            ->where('user_id', 'IN', '(' . implode(', ', $users->toArray()) . ')')
            ->orderBy('created', 'DESC')
            ->fetch();
    }

    /**
     * Post a new message for a user.
     * @param string $message
     * @return bool
     */
    public function message(string $message): bool
    {
        return (new Message([
            'user_id' => $this->get($this->getPrimaryKey()),
            'text' => trim($message),
        ]))->save();
    }

    /**
     * Follow the given user.
     * @param User $user
     * @return bool
     */
    public function follow(User $user): bool
    {
        return (new Following([
            'user_id' => $user->get($user->getPrimaryKey()),
            'follower_id' => $this->get($this->getPrimaryKey()),
        ]))->save();
    }

}