<?php

namespace Lookiero\Hiring\ConsoleTwitter\Applications\Console\Commands;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Applications\Console\Command;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Services\MessageFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Services\UserFinder;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\Exceptions\UserNotFoundException;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Users\Domain\UserName;

/**
 * Class Read
 *
 * Reading: Bob can view Alice’s timeline
 *  > Alice
 *  I love the weather today (5 minutes ago)
 *  > Bob
 *  Good game though. (1 minute ago)
 *  Damn! We lost! (2 minutes ago)
 *
 * @package Lookiero\Hiring\ConsoleTwitter\Console\Commands
 */
class Read extends Command
{
    /**
     * Handle the command execution.
     * @param string $username
     * @return int
     * @throws UserNotFoundException
     * @throws Exception
     */
    public function execute(string $username): int
    {
        $userFinder = new UserFinder($this->users);
        $messageFinder = new MessageFinder($this->messages);

        $user = $userFinder->byName(new UserName($username));
        $messages = $messageFinder->byOwner(new MessageOwnerId($user->id()->value()));

        foreach ($messages as $message) {

            $this->write(sprintf("%s (%s)\n",
                $message->text(),
                $message->created()->asCreatedAgo()
            ));

        }

        return 0;
    }
}