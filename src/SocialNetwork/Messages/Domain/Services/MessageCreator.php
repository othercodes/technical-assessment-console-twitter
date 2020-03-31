<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageCreated;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageText;

/**
 * Class MessageCreator
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services
 */
final class MessageCreator
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
     * Create a new Message.
     * @param MessageId $id
     * @param MessageOwnerId $owner
     * @param MessageText $text
     * @param MessageCreated $created
     * @return Message
     */
    public function create(MessageId $id, MessageOwnerId $owner, MessageText $text, MessageCreated $created): Message
    {
        $message = new Message($id, $owner, $text, $created);
        $this->repository->save($message);
        return $message;
    }
}
