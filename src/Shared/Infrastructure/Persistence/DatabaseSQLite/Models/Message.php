<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Models;

use DateTime;
use Exception;
use Lookiero\Hiring\ConsoleTwitter\Database\ORM\Model;

/**
 * Class Message
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property string created
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Models
 */
class Message extends Model
{
    /**
     * List of attributes of the model
     * @var array
     */
    protected $attributes = [
        'id',
        'user_id',
        'text',
        'created',
    ];

    /**
     * The table name.
     * @var string
     */
    protected $table = 'messages';

    /**
     * Getter (with mutation) fo created field.
     * @param string $created
     * @return string
     * @throws Exception
     */
    public function getCreatedAttribute($created): string
    {
        $now = new DateTime;
        $ago = new DateTime($created);
        $diff = $now->diff($ago);

        $times = ['d' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'];
        $createdAgo = [];

        foreach ($times as $key => $value) {
            if ($diff->$key) {
                $createdAgo[] = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            }
        }

        return implode(', ', $createdAgo) . ' ago';
    }

    /**
     * Returns the string representation of the message.
     * @param bool $owner
     * @return string
     */
    public function toString($owner = false): string
    {
        $message = ($owner)
            ? ucfirst((new User())->find($this->user_id)->username) . ' - '
            : '';

        $message .= "{$this->text} ({$this->created})";

        return $message;
    }
}