<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Messages\Domain;

use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;

/**
 * Class MessagesModuleUnitTestCase
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\SocialNetwork\Messages\Domain
 */
abstract class MessagesModuleUnitTestCase extends MockeryTestCase
{
    /**
     * Mocked repository.
     * @var MessagesRepository
     */
    private $repository;

    /**
     * @param Message $message
     */
    protected function shouldSave(Message $message): void
    {
        $this->repository()
            ->shouldReceive('save')
            ->with($message)
            ->once()
            ->andReturnNull();
    }

    /**
     * @param MessageId $id
     * @param Message|null $message
     */
    protected function shouldSearch(MessageId $id, ?Message $message): void
    {
        $this->repository()
            ->shouldReceive('find')
            ->with($id)
            ->once()
            ->andReturn($message);
    }

    /**
     * @return MessagesRepository|MockInterface
     */
    protected function repository(): MockInterface
    {
        return $this->repository = $this->repository ?: Mockery::mock(MessagesRepository::class);
    }
}
