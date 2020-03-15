<?php

namespace Lookiero\Hiring\ConsoleTwitter\Models;

use Lookiero\Hiring\ConsoleTwitter\Database\ORM\Model;

/**
 * Class Followings
 * @package Lookiero\Hiring\ConsoleTwitter\Models
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