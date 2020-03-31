<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageFinder;
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
     * UserFeedReader constructor.
     * @param UserRepository $users
     * @param MessagesRepository $messages
     */
    public function __construct(UserRepository $users, MessagesRepository $messages)
    {
        $this->userFinder = new UserFinder($users);
        $this->messageFinder = new MessageFinder($messages);
    }

    /**
     * @param $username
     * @return Collection|Message
     * @throws UserNotFoundException
     */
    public function load($username): Collection
    {
        return $this->messageFinder->byOwner(
            new MessageOwnerId($this->userFinder->byName(new UserName($username))->id()->value())
        );
    }
}
