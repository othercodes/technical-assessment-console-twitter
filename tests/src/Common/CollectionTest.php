<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Unit\Common;

use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;
use Lookiero\Hiring\ConsoleTwitter\Common\Collection;

/**
 * Class CollectionTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\Common
 */
class CollectionTest extends TestCase
{
    public function testHas()
    {
        $collection = new Collection(['some_id' => 'some_value']);
        $this->assertTrue($collection->has('some_id'));
        $this->assertFalse($collection->has('some_wrong_id'));
    }

    public function testGet()
    {
        $collection = new Collection(['some_id' => 'some_value']);
        $this->assertEquals('some_value', $collection->get('some_id'));
        $this->assertNull($collection->get('some_null_id'));
    }

    public function testKeys()
    {
        $collection = new Collection([
            'some_id1' => 'some_value1',
            'some_id2' => 'some_value2'
        ]);

        $this->assertIsArray($collection->keys());
        $this->assertCount(2, $collection->keys());
    }

    public function testGetValues()
    {
        $collection = new Collection([
            'some_id1' => 'some_value1',
            'some_id2' => 'some_value2'
        ]);

        $this->assertIsArray($collection->getValues());
        $this->assertCount(2, $collection->getValues());
        $this->assertArrayHasKey('some_id1', $collection->getValues());
        $this->assertArrayHasKey('some_id2', $collection->getValues());
    }

    public function testGetFirstLast()
    {
        $collection = new Collection([
            'some_id1' => 'some_value1',
            'some_id2' => 'some_value2'
        ]);

        $this->assertEquals('some_value1', $collection->first());
        $this->assertEquals('some_value2', $collection->last());

        $collection = new Collection();

        $this->assertNull($collection->first());
        $this->assertNull($collection->last());
    }

    public function testMap()
    {
        $collection = (new Collection([1]))->map(function (int $item) {
            return $item + 1;
        });

        $this->assertEquals(2, $collection->first());
    }

    public function testFilter()
    {
        $collection = (new Collection([1, 2, 3, 4]))->filter(function (int $item) {
            return $item % 2 === 0;
        });

        $this->assertEquals(2, $collection->count());
    }

    public function testMerge()
    {
        $collection = (new Collection([1, 2]))->merge(new Collection([1, 2]));

        $this->assertEquals(4, $collection->count());
    }

    public function testToArray()
    {
        $std = new \stdClass();
        $std->one = 1;
        $std->two = 2;

        $collection = new Collection([$std]);

        $this->assertIsObject($collection);
        $this->assertIsObject($collection->first());

        $array = $collection->toArray();

        $this->assertIsArray($array);
        $this->assertIsArray($array[0]);
    }

    public function testToJson()
    {
        $collection = new Collection([1, 2, 3]);

        $this->assertJson($collection->toJSON());
        $this->assertEquals("[1,2,3]", $collection->toJSON());
    }

    public function testOffsetAccessors()
    {
        $collection = new Collection(['one' => 1]);

        $this->assertEquals(1, $collection->offsetGet('one'));
        $this->assertEquals(1, $collection->count());

        $collection->offsetSet('two', 2);
        $this->assertEquals(2, $collection->count());

        $collection->offsetUnset('two');
        $this->assertEquals(1, $collection->count());

        $this->assertTrue($collection->offsetExists('one'));
        $this->assertFalse($collection->offsetExists('two'));
    }

    public function testTestGetIterator()
    {
        $collection = new Collection(['one' => 1]);

        $this->assertInstanceOf(\ArrayIterator::class, $collection->getIterator());
    }
}