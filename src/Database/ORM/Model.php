<?php

namespace Lookiero\Hiring\ConsoleTwitter\Database\ORM;

use Lookiero\Hiring\ConsoleTwitter\Application\Container;
use Lookiero\Hiring\ConsoleTwitter\Common\Collection;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Connector as ConnectorContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Contracts\Query as QueryContract;
use Lookiero\Hiring\ConsoleTwitter\Database\Query;

/**
 * Class Model
 * @method $this select(array $columns = ['*'])
 * @method $this where(string $column, string $operator, $value, $quoted = false)
 * @method $this groupBy(string $field)
 * @method $this orderBy(string $field, string $order = 'DESC')
 * @method $this limit(int $limit = 1000, ?int $offset = null)
 * @package Lookiero\Hiring\ConsoleTwitter\Models
 */
abstract class Model extends Container
{
    /**
     * Model primary key.
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * Model timestamp field.
     * @var string
     */
    protected $timestamp = 'created';

    /**
     * The table name.
     * @var string
     */
    protected $table;

    /**
     * List of attributes of the model
     * @var array
     */
    protected $attributes = [];

    /**
     * Query to execute.
     * @var QueryContract
     */
    protected $query;

    /**
     * Database connector.
     * @var ConnectorContract
     */
    protected static $connector;

    /**
     * Model constructor.
     * @param array $source
     */
    public function __construct(array $source = [])
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[$attribute] = isset($source[$attribute]) ? $source[$attribute] : null;
        }

        parent::__construct($attributes);
    }

    /************************************************
     *              Setters and Getters
     ************************************************/

    /**
     * Get the required value by id.
     * @param mixed $id
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($id, $default = null)
    {
        if (!$this->has($id)) {
            return $default;
        }

        $method = $this->getProxyMethod('get', $id);
        return ($this->canCall($method))
            ? $this->__call($method, [$this->values[$id]])
            : $this->values[$id];
    }

    /**
     * Set the required value by id.
     * @param mixed $id
     * @param mixed $value
     * @return Model
     */
    public function set($id, $value): Model
    {
        $method = $this->getProxyMethod('set', $id);
        $this->values[$id] = (($this->canCall($method))
            ? $this->__call($method, [$value])
            : $value);

        return $this;
    }

    /**
     * Return the required proxy method.
     * @param string $prefix
     * @param string $id
     * @param string $suffix
     * @return string
     */
    protected function getProxyMethod(string $prefix, string $id, string $suffix = 'Attribute'): string
    {
        return trim(
            lcfirst($prefix)
            . implode('', array_map(function (string $item) {
                return ucfirst($item);
            }, explode('.', $id)))
            . ucfirst($suffix)
        );
    }

    /************************************************
     *              Dynamic Calls
     ************************************************/

    /**
     * Check if the required method name is callable.
     * @param string $method
     * @return bool
     */
    protected function canCall(string $method): bool
    {
        return method_exists($this, $method);
    }

    /**
     * Execute arbitrary method.
     * @param string $method
     * @param array $arguments
     * @return $this|null
     */
    public function __call(string $method, array $arguments)
    {
        /**
         * Check if the method can be called locally in the model,
         * i.e: custom getters and setter.
         */
        if ($this->canCall($method)) {
            return call_user_func_array([$this, $method], $arguments);
        }

        /**
         * Nex check if the called method is in this list of available methods on query,
         * if yes, execute it over the query instance, if is not initialized, do it.
         */
        if (in_array($method, ['select', 'where', 'groupBy', 'orderBy', 'limit'])) {
            call_user_func_array([$this->query(), $method], $arguments);
            return $this;
        }

        return null;
    }

    /************************************************
     *           Database Operations
     ************************************************/

    /**
     * Set the connector into the ORM model.
     * @param ConnectorContract $db
     * @return ConnectorContract
     */
    public static function setConnector(ConnectorContract $db): ConnectorContract
    {
        self::$connector = $db;
        return $db;
    }

    /**
     * Return the Connection.
     * @return ConnectorContract
     */
    public function getConnection(): ?ConnectorContract
    {
        return self::$connector;
    }

    /**
     * Return the table name.
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table;
    }

    /**
     * Return the primary key.
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primary_key;
    }

    /**
     * Return the timestamp.
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * Return an assoc array of the model values, without timestamp and pk.
     * @return array
     */
    public function getAttributesValues()
    {
        $values = [];
        foreach ($this->attributes as $attribute) {
            if (!in_array($attribute, [$this->getPrimaryKey(), $this->getTimestamp()])) {
                $value = $this->get($attribute);
                $values[$attribute] = is_string($value) ? "'{$value}'" : $value;
            }
        }
        return $values;
    }

    /**
     * Initialize a query for the model.
     * @param bool $new
     * @return QueryContract
     */
    public function query($new = false): QueryContract
    {
        if (!($this->query instanceof QueryContract) || $new) {
            $this->query = new Query(self::$connector);
        }

        return $this->query;
    }

    /**
     * Resolve the query and resolve the given query.
     * @param array $parameters
     * @return Collection
     */
    public function fetch(array $parameters = []): Collection
    {
        $items = $this->query()
            ->from([$this->getTableName()])
            ->exec($parameters, static::class);

        $this->query(true);

        return ($items instanceof Collection) ? $items : new Collection();
    }

    /**
     * Find a model by Id.
     * @param $id
     * @return static
     */
    public function find($id): ?Model
    {
        $fetch = $this->query()
            ->from([$this->getTableName()])
            ->select(['*'])
            ->where($this->getPrimaryKey(), '=', $id)
            ->exec([], static::class);

        if ($fetch instanceof Collection) {
            return $fetch->first();
        }

        return null;
    }

    /**
     * Save the current data model into th database.
     * @return bool
     */
    public function save(): bool
    {
        return $this->query()
                ->insert($this->getTableName(), $this->getAttributesValues())
                ->exec() !== false;
    }

    /**
     * Update the current model.
     * @return bool
     */
    public function update(): bool
    {
        return $this->query()
                ->update($this->getTableName(), $this->getAttributesValues())
                ->where($this->getPrimaryKey(), '=', $this->get($this->getPrimaryKey()))
                ->exec() !== false;
    }

    /**
     * Delete the current model by id.
     * @return bool
     */
    public function delete(): bool
    {
        return $this->query(true)
                ->delete()
                ->where($this->getPrimaryKey(), '=', $this->get($this->getPrimaryKey()))
                ->exec() !== false;
    }

}