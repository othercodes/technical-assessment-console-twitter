<?php

namespace Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Infrastructure\Persistence;

use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Connector;
use Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Query;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Contracts\MessagesRepository;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\Message;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageCreatedTime;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageOwnerId;
use Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Domain\MessageText;

/**
 * Class DatabaseSQLiteMessagesRepository
 * @package Lookiero\Hiring\ConsoleTwitter\SocialNetwork\Messages\Infrastructure\Persistence
 */
class DatabaseSQLiteMessagesRepository implements MessagesRepository
{
    /**
     * The Database connector instance.
     * @var Connector
     */
    private $connector;

    /**
     * DatabaseSQLiteMessagesRepository constructor.
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Find a Message by MessageId.
     * @param MessageId $id
     * @return Message|null
     */
    public function find(MessageId $id): ?Message
    {
        return $this->search([
            ['field' => 'id', 'operator' => '=', 'value' => quote($id->value())]
        ])->first();
    }

    /**
     * Search Messages by the given array of criteria.
     * @param array $criteria
     * @return Collection|Message
     */
    public function search(array $criteria = []): Collection
    {
        try {
            $query = new Query();
            $query->select(['*'])->from(['messages']);

            foreach ($criteria as $filter) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }

            return (new Collection($this->connector->execute($query)))
                ->map(function (array $item) {
                    return new Message(
                        new MessageId($item['id']),
                        new MessageOwnerId($item['owner_id']),
                        new MessageText($item['text']),
                        new MessageCreatedTime($item['created'])
                    );
                });
        } catch (\Exception $e) {
            // add some log to control fail
            return new Collection();
        }
    }

    /**
     * Persists the given Message.
     * @param Message $message
     */
    public function save(Message $message): void
    {
        try {
            $query = new Query();
            $query->insert('messages', [
                'id' => quote($message->id()->value()),
                'owner_id' => quote($message->owner()->value()),
                'text' => quote($message->text()->value()),
                'created' => quote($message->created()->format('Y-m-d H:i:s'))
            ]);

            $this->connector->execute($query);
        } catch (\Exception $e) {
            // add some log to control fail
        }
    }

    /**
     * Delete the given Message by Id.
     * @param MessageId $id
     */
    public function delete(MessageId $id): void
    {
        try {
            $query = new Query();
            $query->delete()
                ->from('messages')
                ->where('id', '=', quote($id->value()));

            $this->connector->execute($query);
        } catch (\Exception $e) {
            // add some log to control fail
        }
    }
}
