<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;

/**
 * Class MessageFinder
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services
 */
final class MessageFinder
{
    /**
     * The message repository
     * @var MessagesRepository
     */
    private $repository;

    /**
     * MessageCreator constructor.
     * @param MessagesRepository $repository
     */
    public function __construct(MessagesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Search and return all the messages for the given Owner Id.
     * @param MessageOwnerId ...$ownerIds
     * @return Collection|Message
     */
    public function byOwner(MessageOwnerId ...$ownerIds): Collection
    {
        $ownerIds = new Collection($ownerIds);
        $ownerIds = implode(',', $ownerIds->map(function (MessageOwnerId $id) {
            return quote($id);
        })->getValues());

        return $this->repository->search([
            ['field' => 'owner_id', 'operator' => 'IN', 'value' => "($ownerIds)"]
        ]);
    }
}
