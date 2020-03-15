<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Database;

use Lookiero\Hiring\ConsoleTwitter\Database\Query;
use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;

/**
 * Class QueryTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\Database
 */
class QueryTest extends TestCase
{
    public function testSelect()
    {
        $this->assertEquals('SELECT *', (new Query())->select(['*'])->compile());
        $this->assertEquals('SELECT id,name', (new Query())->select(['id', 'name'])->compile());
    }

    public function testUpdate()
    {
        $this->assertEquals('UPDATE users SET username = newName', (new Query())->update('users', [
            'username' => 'newName'
        ])->compile());
    }

    public function testDelete()
    {
        $this->assertEquals('DELETE', (new Query())->delete()->compile());
    }

    public function testInsert()
    {
        $this->assertEquals('INSERT INTO users (`username`) VALUES (alice)', (new Query())->insert('users', [
            'username' => 'alice',
        ])->compile());
    }

    public function testFrom()
    {
        $this->assertEquals('FROM users', (new Query())->from(['users'])->compile());
        $this->assertEquals('FROM users, messages', (new Query())->from(['users', 'messages'])->compile());
    }

    public function testWhere()
    {
        $query = (new Query())->where('name', '=', 'vincent', true)->where('id', '=', 42);
        $this->assertEquals('WHERE name = "vincent" AND id = 42', $query->compile());
    }

    public function testWhereColumnInvalid()
    {
        $this->expectException(\Lookiero\Hiring\ConsoleTwitter\Database\Exceptions\QueryException::class);
        (new Query())->where('username', 'NOT_VALID', 'vincent')->compile();
    }

    public function testGroupBy()
    {
        $query = (new Query())->groupBy('username');
        $this->assertEquals('GROUP BY username', $query->compile());
    }

    public function testOrderBy()
    {
        $query = (new Query())->orderBy('username', 'DESC');
        $this->assertEquals('ORDER BY username DESC', $query->compile());

        $query = (new Query())->orderBy('surname', 'ASC');
        $this->assertEquals('ORDER BY surname ASC', $query->compile());
    }

    public function testOrderByInvalidOrder()
    {
        $this->expectException(\Lookiero\Hiring\ConsoleTwitter\Database\Exceptions\QueryException::class);
        (new Query())->orderBy('username', 'INVALID_ORDER')->compile();
    }

    public function testLimit()
    {
        $query = (new Query())->limit(100, 1000);
        $this->assertEquals('LIMIT 1000 100', $query->compile());
    }

    public function testToString()
    {
        $query = (new Query())->select(['*'])->from(['users'])->where('username', '=', 'vincent', true);
        $this->assertEquals('SELECT * FROM users WHERE username = "vincent"', (string)$query);
    }

    public function testToStringException()
    {
        $query = (new Query())->select(['*'])->from(['users'])->where('username', 'INVALID', 'vincent', true);
        $this->assertEquals('Invalid operator in where statement.', (string)$query);
    }
}