<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;

/**
 * Interface MessagesRepository
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts
 */
interface MessagesRepository
{
    /**
     * Find a Message by MessageId.
     * @param MessageId $id
     * @return Message|null
     */
    public function find(MessageId $id): ?Message;

    /**
     * Search a Message by the given array of criteria.
     * @param array $criteria
     * @return mixed
     */
    public function search(array $criteria = []): Collection;

    /**
     * Persists the given Message.
     * @param Message $message
     */
    public function save(Message $message): void;

    /**
     * Delete the given Message by Id.
     * @param MessageId $id
     */
    public function delete(MessageId $id): void;
}
