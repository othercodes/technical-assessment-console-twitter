<?php

namespace Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite;

use InvalidArgumentException;
use PDO;

/**
 * Class Connector (Only for SQLite dont want to build any fancy database abstraction layer for all drivers)
 * @package Lookiero\Hiring\ConsoleTwitter\Shared\Infrastructure\Persistence\DatabaseSQLite;
 */
class Connector
{
    /**
     * Database connection
     * @var PDO
     */
    protected $connection;

    /**
     * Default options.
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
    ];

    /**
     * Enable the query debug.
     * @var bool
     */
    private $debug = false;

    /**
     * Connector constructor.
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $username = isset($configuration['username']) ? $configuration['username'] : null;
        $password = isset($configuration['password']) ? $configuration['password'] : null;

        $this->debug = isset($configuration['debug']) ? $configuration['debug'] : false;

        $options = $this->options + (isset($configuration['options']) ? $configuration['options'] : []);

        if ($configuration['name'] == ':memory:') {
            $dsn = 'sqlite::memory:';

        } else {
            $path = realpath($configuration['name']);

            if ($path === false) {
                throw new InvalidArgumentException("Database {$configuration['name']} does not exist.");
            }

            $dsn = 'sqlite:' . $path;
        }

        $this->connection = new PDO($dsn, $username, $password, $options);
    }

    /**
     * Return the PDO connection.
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Execute the given query and return the result.
     * @param Query|string $query
     * @param array $parameters
     * @return array|null
     * @throws Exceptions\QueryException
     */
    public function execute($query, array $parameters = []): ?array
    {
        $query = ($query instanceof Query) ? $query->compile() : $query;

        if ($this->debug) {
            var_dump($query);
        }

        $statement = $this->connection->prepare($query);
        return ($statement->execute($parameters))
            ? $statement->fetchAll(PDO::FETCH_ASSOC)
            : null;
    }
}