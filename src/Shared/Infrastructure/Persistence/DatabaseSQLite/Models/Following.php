<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Models;

use Lookiero\Hiring\ConsoleTwitter\Database\ORM\Model;

/**
 * Class Following
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Models
 */
class Following extends Model
{
    /**
     * List of attributes of the model
     * @var array
     */
    protected $attributes = [
        'id',
        'user_id',
        'follower_id',
        'created',
    ];

    /**
     * The table name.
     * @var string
     */
    protected $table = 'followings';
}