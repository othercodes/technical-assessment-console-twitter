<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Unit\Application;

use Lookiero\Hiring\ConsoleTwitter\Application\Container;
use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;

/**
 * Class ContainerTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests
 */
class ContainerTest extends TestCase
{
    public function testSetHas()
    {
        $container = new Container();
        $container->set('one', function () {
            return 1;
        });

        $this->assertTrue($container->has('one'));
        $this->assertFalse($container->has('two'));
    }

    public function testGet()
    {
        $container = new Container();
        $container->set('one', function () {
            return 1;
        });

        $this->assertEquals(2, $container->get('two', 2));
        $this->assertEquals(1, $container->get('one'));
    }

    public function testRemove()
    {
        $container = new Container();
        $container->set('one', function () {
            return 1;
        });

        $this->assertCount(1, $container);

        $container->remove('one');
        $this->assertCount(0, $container);
    }

    public function testOffsetAccessors()
    {
        $container = new Container(['one' => 1]);
        $container->set('one', function () {
            return 1;
        });

        $this->assertEquals(1, $container->__get('one'));
        $this->assertCount(1, $container);

        $container->__set('two', 2);
        $this->assertCount(2, $container);

        $container->__unset('two');
        $this->assertCount(1, $container);

        $this->assertTrue($container->__isset('one'));
        $this->assertFalse($container->__isset('two'));
    }
}