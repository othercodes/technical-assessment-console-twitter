<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Application;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageCreatedTime;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageText;

/**
 * Class MessageCreator
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Application
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
     * @param MessageOwnerId $ownerId
     * @param MessageText $text
     * @param MessageCreatedTime $created
     */
    public function create(MessageId $id, MessageOwnerId $ownerId, MessageText $text, MessageCreatedTime $created)
    {
        $this->repository->save(new Message($id, $ownerId, $text, $created));
    }
}