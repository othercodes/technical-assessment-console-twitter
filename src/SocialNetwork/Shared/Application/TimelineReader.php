<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application;

use Exception;
use Generator;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application\Contracts\Formatter;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class TimelineReader
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application
 */
final class TimelineReader
{
    /**
     * User Finder (Domain Service)
     * @var UserFinder
     */
    private $userFinder;

    /**
     * Message Finder (Domain Service)
     * @var MessageFinder
     */
    private $messageFinder;

    /**
     * Message formatter.
     * @var Formatter
     */
    private $formatter;

    /**
     * TimelineReader constructor.
     * @param UserRepository $users
     * @param MessagesRepository $messages
     * @param Formatter $formatter
     */
    public function __construct(UserRepository $users, MessagesRepository $messages, Formatter $formatter)
    {
        $this->userFinder = new UserFinder($users);
        $this->messageFinder = new MessageFinder($messages);

        $this->formatter = $formatter;
    }

    /**
     * @param string $username
     * @return Generator
     * @throws Exception
     * @throws UserNotFoundException
     */
    public function readFrom(string $username): Generator
    {
        /** @var Message[] $messages */
        $messages = $this->messageFinder->byOwner(
            new MessageOwnerId($this->userFinder->byName(new UserName($username))->id())
        );

        foreach ($messages as $message) {
            yield $this->formatter->format('{message} ({time})', [
                'message' => $message->text(),
                'time' => $message->created()->asCreatedAgo()
            ]);
        }
    }
}
