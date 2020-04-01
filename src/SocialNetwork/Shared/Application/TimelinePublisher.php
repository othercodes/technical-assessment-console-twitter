<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageCreated;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageText;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageCreator;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Contracts\UserRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class TimelinePublisher
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Shared\Application
 */
final class TimelinePublisher
{
    /**
     * User Finder (Domain Service)
     * @var UserFinder
     */
    private $userFinder;

    /**
     * Message Creator (Domain Service)
     * @var MessageCreator
     */
    private $messageCreator;

    /**
     * UserFeedReader constructor.
     * @param UserRepository $users
     * @param MessagesRepository $messages
     */
    public function __construct(UserRepository $users, MessagesRepository $messages)
    {
        $this->userFinder = new UserFinder($users);
        $this->messageCreator = new MessageCreator($messages);
    }

    /**
     * Publish a new Message for a User
     * @param string $username
     * @param string $message
     * @return bool
     * @throws UserNotFoundException
     */
    public function publish(string $username, string $message): bool
    {
        $user = $this->userFinder->byName(new UserName($username));
        $this->messageCreator->create(
            new MessageId(uuid()),
            new MessageOwnerId($user->id()),
            new MessageText($message),
            new MessageCreated('now')
        );

        return true;
    }
}
