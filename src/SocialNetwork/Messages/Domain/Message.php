<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain;

/**
 * Class Message (Aggregate)
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain
 */
final class Message
{
    /**
     * The message Id.
     * @var MessageId
     */
    private $id;

    /**
     * The message owner Id.
     * @var MessageOwnerId
     */
    private $ownerId;

    /**
     * The message text.
     * @var MessageText
     */
    private $text;

    /**
     * The message created timestamp.
     * @var MessageCreatedTime
     */
    private $created;

    /**
     * Message constructor.
     * @param MessageId $id
     * @param MessageOwnerId $ownerId
     * @param MessageText $text
     * @param MessageCreatedTime $created
     */
    public function __construct(MessageId $id, MessageOwnerId $ownerId, MessageText $text, MessageCreatedTime $created)
    {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->text = $text;
        $this->created = $created;
    }

    /**
     * The message Id
     * @return MessageId
     */
    public function id(): MessageId
    {
        return $this->id;
    }

    /**
     * The owner Id
     * @return MessageOwnerId
     */
    public function owner(): MessageOwnerId
    {
        return $this->ownerId;
    }

    /**
     * The message text
     * @return MessageText
     */
    public function text(): MessageText
    {
        return $this->text;
    }

    /**
     * The created time.
     * @return MessageCreatedTime
     */
    public function created(): MessageCreatedTime
    {
        return $this->created;
    }
}