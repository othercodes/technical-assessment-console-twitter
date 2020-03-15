<?php

namespace Lookiero\Hiring\ConsoleTwitter\Database\Contracts;

use Lookiero\Hiring\ConsoleTwitter\Common\Contracts\Collection;
use Lookiero\Hiring\ConsoleTwitter\Database\Exceptions\QueryException;
use stdClass;

/**
 * Interface Query
 * @package Lookiero\Hiring\ConsoleTwitter\Database\Contracts
 */
interface Query
{
    /**
     * Add SELECT clause
     * @param array $columns
     * @return Query
     */
    public function select(array $columns = ['*']): Query;

    /**
     * Add UPDATE statement
     * @param string $table
     * @param array $columns
     * @return Query
     */
    public function update(string $table, array $columns): Query;

    /**
     * Add a DELETE statement
     * @return Query
     */
    public function delete(): Query;

    /**
     * Add INSERT statement
     * @param string $table
     * @param array $columns
     * @return Query
     */
    public function insert(string $table, array $columns): Query;

    /**
     * Add FROM statement
     * @param array|string $tables
     * @return Query
     */
    public function from(array $tables): Query;

    /**
     * Add a new WHERE/AND statement
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @param $quoted
     * @return Query
     */
    public function where(string $column, string $operator, $value, $quoted = false): Query;

    /**
     * Add GROUP BY statement
     * @param string $field
     * @return Query
     */
    public function groupBy(string $field): Query;

    /**
     * Add ORDER BY statement
     * @param string $field
     * @param string $order
     * @return Query
     */
    public function orderBy(string $field, string $order = 'DESC'): Query;

    /**
     * Set the LIMIT statement
     * @param int $limit
     * @param null|int $offset
     * @return Query
     */
    public function limit(int $limit = 1000, ?int $offset = null): Query;

    /**
     * Compile the SQL query
     * @return string
     * @throws QueryException
     */
    public function compile(): string;

    /**
     * Execute the Query.
     * @param array $parameters
     * @param string $class
     * @return bool|Collection
     */
    public function exec(array $parameters = [], string $class = stdClass::class);
}