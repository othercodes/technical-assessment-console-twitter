<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Database\Query;
use Lookiero\Hiring\ConsoleTwitter\Shared\Domain\Collection;
use PDO;
use stdClass;

/**
 * Interface Connector
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite\Contracts
 */
interface Connector
{
    /**
     * Database connection.
     * @param array $configuration
     * @return PDO
     */
    public function connect(array $configuration): PDO;

    /**
     * Execute the given query and return the result as new data container.
     * @param Query|string $query
     * @param array $parameters
     * @param string $class
     * @return Collection|bool
     */
    public function execute($query, array $parameters = [], string $class = stdClass::class);
}