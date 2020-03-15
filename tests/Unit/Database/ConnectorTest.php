<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Database;

use Lookiero\Hiring\ConsoleTwitter\Common\Collection;
use Lookiero\Hiring\ConsoleTwitter\Database\Connector;
use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;

/**
 * Class ConnectorTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\Database
 */
class ConnectorTest extends TestCase
{
    public function testConnectionSuccessOnMemory()
    {
        $connector = new Connector(['name' => ':memory:']);
        $this->assertInstanceOf(\PDO::class, $connector->getConnection());
    }

    public function testConnectionSuccessOnFile()
    {
        $this->assertTrue(touch(__DIR__ . '/database.sqlite'));

        $connector = new Connector(['name' => __DIR__ . '/database.sqlite',]);
        $this->assertInstanceOf(\PDO::class, $connector->getConnection());

        $this->assertTrue(unlink(__DIR__ . '/database.sqlite'));
    }

    public function testConnectionFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Connector(['name' => 'WRONG_FILE_DATABASE_NAME',]);
    }

    public function testExecute()
    {
        $connector = new Connector(['name' => ':memory:']);
        $result = $connector->execute('CREATE TABLE IF NOT EXISTS users (username VARCHAR(32) UNIQUE)');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);

        $result = $connector->execute('INSERT INTO users (username) VALUES ("vincent")');
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);

        $result = $connector->execute('SELECT * FROM users');
        $this->assertInstanceOf(Collection::class, $result);

        foreach ($result as $item) {
            $this->assertInstanceOf(\stdClass::class, $item);
        }
    }
}