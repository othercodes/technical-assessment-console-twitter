<?php

namespace Lookiero\Hiring\ConsoleTwitter\Database;

use Exception;
use Lookiero\Hiring\ConsoleTwitter\Common\Collection;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Connector as ConnectorContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Query as QueryContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Exceptions\QueryException;
use stdClass;

/**
 * Class Query
 * @package Lookiero\Hiring\ConsoleTwitter\Database
 */
class Query implements QueryContract
{

    /**
     * Allowed operators
     * @var array
     */
    private $operators = ['=', '<', '>', '<=', '>=', '<>', '!=', 'LIKE', 'IN'];

    /**
     * Select values
     * @var array
     */
    private $select;

    /**
     * Update values
     * @var array
     */
    private $update;

    /**
     * Delete values
     * @var array
     */
    private $delete;

    /**
     * Insert values
     * @var array
     */
    private $insert;

    /**
     * From values
     * @var array
     */
    private $tables;

    /**
     * Where conditions
     * @var array
     */
    private $where = [];

    /**
     * Order by values
     * @var array
     */
    private $order = [];

    /**
     * Groups values
     * @var array
     */
    private $group;

    /**
     * Limit values
     * @var array
     */
    private $limit;

    /**
     * Database connector.
     * @var ConnectorContract
     */
    protected $connector;

    /**
     * Query constructor.
     * @param ConnectorContract|null $connector
     */
    public function __construct(?ConnectorContract $connector = null)
    {
        $this->connector = $connector;
    }

    /**
     * Add SELECT clause
     * @param array $columns
     * @return Query
     */
    public function select(array $columns = ['*']): QueryContract
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Add UPDATE statement
     * @param string $table
     * @param array $columns
     * @return Query
     */
    public function update(string $table, array $columns): QueryContract
    {
        $this->tables = [$table];
        $this->update = $columns;
        return $this;
    }

    /**
     * Add a DELETE statement
     * @return Query
     */
    public function delete(): QueryContract
    {
        $this->delete = true;
        return $this;
    }

    /**
     * Add INSERT statement
     * @param string $table
     * @param array $columns
     * @return Query
     */
    public function insert(string $table, array $columns): QueryContract
    {
        $this->tables = [$table];
        $this->insert = $columns;
        return $this;
    }

    /**
     * Add FROM statement
     * @param array|string $tables
     * @return Query
     */
    public function from(array $tables): QueryContract
    {
        $this->tables = $tables;
        return $this;
    }

    /**
     * Add a new WHERE/AND statement
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param bool $quoted
     * @return Query
     */
    public function where(string $column, string $operator, $value, $quoted = false): QueryContract
    {
        $this->where[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'quoted' => $quoted
        ];
        return $this;
    }

    /**
     * Add GROUP BY statement
     * @param string $field
     * @return Query
     */
    public function groupBy(string $field): QueryContract
    {
        $this->group = $field;
        return $this;
    }

    /**
     * Add ORDER BY statement
     * @param string $field
     * @param string $order
     * @return Query
     */
    public function orderBy(string $field, string $order = 'DESC'): QueryContract
    {
        $this->order[] = [$field, $order];
        return $this;
    }

    /**
     * Set the LIMIT statement
     * @param int $limit
     * @param null|int $offset
     * @return Query
     */
    public function limit(int $limit = 1000, ?int $offset = null): QueryContract
    {
        $this->limit = [$offset, $limit];
        return $this;
    }

    /**
     * Compile the SQL query
     * @return string
     * @throws QueryException
     */
    public function compile(): string
    {
        $sql = [];

        $queryType = 'select';

        if (isset($this->select)) {
            $sql[] = 'SELECT ' . implode(',', $this->select);
        }

        if (isset($this->update)) {

            $queryType = 'update';

            $sql[] = 'UPDATE';
            $sql[] = implode(', ', $this->tables);
            $sql[] = 'SET';

            $blocks = [];
            foreach ($this->update as $key => $value) {
                $blocks[] = $key . ' = ' . $value;
            }

            $sql[] = implode(',', $blocks);
        }

        if (isset($this->delete)) {
            $sql[] = "DELETE ";
        }

        if (isset($this->insert)) {

            $queryType = 'insert';

            $sql[] = 'INSERT INTO';
            $sql[] = implode(', ', $this->tables);

            $fields = [];
            $replaces = [];

            foreach ($this->insert as $key => $value) {
                $fields[] = '`' . $key . '`';
                $replaces[] = $value;
            }

            $sql[] = '(' . implode(',', $fields) . ') VALUES (' . implode(',', $replaces) . ')';
        }

        if (isset($this->tables) && $queryType === 'select') {
            $sql[] = 'FROM ' . implode(', ', $this->tables);
        }

        foreach ($this->where as $index => $where) {

            if (!is_string($where['operator']) || !in_array($where['operator'], $this->operators)) {
                throw new QueryException("Invalid operator in where statement.");
            }

            if (gettype($where['value']) == 'string' && $where['quoted'] === true) {
                $where['value'] = '"' . $where['value'] . '"';
            }

            unset($where['quoted']);

            $sql[] = (($index == 0) ? "WHERE " : "AND ") . implode(" ", $where);

        }

        if (isset($this->group)) {
            $sql[] = 'GROUP BY ' . $this->group;
        }

        foreach ($this->order as $index => $order) {

            if (!in_array($order[1], ['ASC', 'DESC'])) {
                throw new QueryException('Invalid order by value, must be ASC or DESC.');
            }

            $sql[] = 'ORDER BY ' . implode(" ", $order);
        }

        if (isset($this->limit)) {
            $sql[] = 'LIMIT ' . implode(" ", $this->limit);
        }

        return trim(implode(" ", $sql));
    }

    /**
     * Execute the Query.
     * @param array $parameters
     * @param string $class
     * @return bool|Collection
     */
    public function exec(array $parameters = [], string $class = stdClass::class)
    {
        if ($this->connector instanceof ConnectorContract) {
            return $this->connector->execute($this, $parameters, $class);
        }
        return null;
    }

    /**
     * Return the final sql string
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->compile();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}