<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain;

use DateTimeImmutable;
use Exception;

/**
 * Class MessageCreated
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain
 */
final class MessageCreated extends \DateTimeImmutable
{
    /**
     * Return the created time in ago format.
     * @return string
     * @throws Exception
     */
    public function asCreatedAgo(): string
    {
        $now = new DateTimeImmutable();
        $diff = $now->diff($this);

        $times = ['d' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'];
        $createdAgo = [];

        foreach ($times as $key => $value) {
            if ($diff->$key) {
                $createdAgo[] = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
            }
        }

        return implode(', ', $createdAgo) . ' ago';
    }
}
