<?php

namespace Lookiero\Hiring\ConsoleTwitter\Database\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Common\Contracts\Collection as CollectionContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Query;
use PDO;
use stdClass;

/**
 * Interface Connector
 * @package Lookiero\Hiring\ConsoleTwitter\Database\Contracts
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
     * @return CollectionContract|bool
     */
    public function execute($query, array $parameters = [], string $class = stdClass::class);
}