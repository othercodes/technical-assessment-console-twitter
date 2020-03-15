<?php

namespace Lookiero\Hiring\ConsoleTwitter\Database;

use InvalidArgumentException;
use Lookiero\Hiring\ConsoleTwitter\Common\Collection;
use Lookiero\Hiring\ConsoleTwitter\Common\Contracts\Collection as CollectionContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Connector as ConnectorContract;
use PDO;
use stdClass;

/**
 * Class Connector (Only for SQLite dont want to build any fancy database abstraction layer for all drivers)
 * @package Lookiero\Hiring\ConsoleTwitter\Database
 */
class Connector implements ConnectorContract
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
        $this->connection = $this->connect($configuration);
    }

    /**
     * Start the connection with database.
     * @param array $configuration
     * @return PDO
     */
    public function connect(array $configuration): PDO
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

        return new PDO($dsn, $username, $password, $options);
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
     * Execute the given query and return the result as new data container.
     * @param Query|string $query
     * @param array $parameters
     * @param string $class
     * @return CollectionContract|bool
     * @throws Exceptions\QueryException
     */
    public function execute($query, array $parameters = [], string $class = stdClass::class)
    {
        $statement = $this->connection->prepare(($query instanceof Query) ? $query->compile() : $query);
        if ($statement->execute($parameters)) {

            $collection = new Collection($statement->fetchAll(PDO::FETCH_ASSOC));
            if (class_exists($class, true)) {
                $collection = $collection->map(function (array $row) use ($class) {
                    $object = new $class;
                    foreach ($row as $field => $value) {
                        $object->{$field} = $value;
                    }
                    return $object;
                });
            }
            return $collection;
        }

        return false;
    }
}