<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Database\ORM;

use Lookiero\Hiring\ConsoleTwitter\Database\ORM\Model;
use Lookiero\Hiring\ConsoleTwitter\Database\Query;
use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;

/**
 * Class ModelTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests\Database\ORM
 */
class ModelTest extends TestCase
{
    public function testModelInstantiation()
    {
        $model = new class () extends Model {

            protected $table = 'users';

            protected $attributes = ['username'];

            public function getUsernameAttribute($username)
            {
                return strtoupper($username);
            }

            public function setUsernameAttribute($username)
            {
                return strtolower($username);
            }
        };
        $this->assertInstanceOf(Model::class, $model);

        $this->assertInstanceOf(Model::class, $model->set('username', 'vincent'));
        $this->assertInstanceOf(Model::class, $model->set('quote', 'Ezekiel 25:17'));

        $this->assertEquals('VINCENT', $model->get('username'));
        $this->assertEquals('vega', $model->get('surname', 'vega'));
    }

    public function testGetTableName()
    {
        $model = new class () extends Model {

            protected $table = 'users';

            protected $attributes = ['username'];

            public function getUsernameAttribute($username)
            {
                return strtoupper($username);
            }

            public function setUsernameAttribute($username)
            {
                return strtolower($username);
            }
        };
        $this->assertInstanceOf(Model::class, $model);

        $this->assertEquals('users', $model->getTableName());
    }

    public function testGetPrimaryKey()
    {
        $model = new class () extends Model {

            protected $table = 'users';

            protected $attributes = ['username'];

            public function getUsernameAttribute($username)
            {
                return strtoupper($username);
            }

            public function setUsernameAttribute($username)
            {
                return strtolower($username);
            }
        };
        $this->assertInstanceOf(Model::class, $model);

        $this->assertEquals('id', $model->getPrimaryKey());
    }

    public function testGetTimestamp()
    {
        $model = new class () extends Model {

            protected $table = 'users';

            protected $attributes = ['username'];

            public function getUsernameAttribute($username)
            {
                return strtoupper($username);
            }

            public function setUsernameAttribute($username)
            {
                return strtolower($username);
            }
        };
        $this->assertInstanceOf(Model::class, $model);

        $this->assertEquals('created', $model->getTimestamp());
    }

    public function testGetAttributesValues()
    {
        $model = new class () extends Model {

            protected $table = 'users';

            protected $attributes = ['username'];

            public function getUsernameAttribute($username)
            {
                return strtoupper($username);
            }

            public function setUsernameAttribute($username)
            {
                return strtolower($username);
            }
        };
        $this->assertInstanceOf(Model::class, $model);

        $this->assertIsArray($model->getAttributesValues());
        $this->assertCount(1, $model->getAttributesValues());
    }

    public function testQuery()
    {
        $model = new class () extends Model {

            protected $table = 'users';

            protected $attributes = ['username'];

            public function getUsernameAttribute($username)
            {
                return strtoupper($username);
            }

            public function setUsernameAttribute($username)
            {
                return strtolower($username);
            }
        };
        $this->assertInstanceOf(Model::class, $model);

        $this->assertInstanceOf(Query::class, $model->query());
    }
}