<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageCreator;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageCreatedTime;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageText;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class Post
 *
 * Posting: Alice can publish messages to a personal timeline.
 *  > Alice -> I love the weather today
 *  > Bob -> Damn! We lost!
 *  > Bob -> Good game though.
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Commands
 */
class Post extends Command
{
    /**
     * Handle the command execution.
     * @param string $username
     * @param string $message
     * @return int
     * @throws UserNotFoundException
     */
    public function execute(string $username, string $message): int
    {
        $userFinder = new UserFinder($this->users);
        $user = $userFinder->byName(new UserName($username));

        $messageCreator = new MessageCreator($this->messages);
        $messageCreator->create(
            new MessageId(uuid()),
            new MessageOwnerId($user->id()->value()),
            new MessageText($message),
            new MessageCreatedTime('now')
        );

        return 0;
    }
}