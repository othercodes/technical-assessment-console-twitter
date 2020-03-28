<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Unit\Application;

use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;
use Lookiero\Hiring\ConsoleTwitter\Application\Container;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Container as ContainerContract;
use Lookiero\Hiring\ConsoleTwitter\Application\Provider;

/**
 * Class ProviderTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests
 */
class ProviderTest extends TestCase
{
    public function testInvoke()
    {
        $provider = new class () extends Provider {
            public function load(ContainerContract $container)
            {
                return new \stdClass();
            }
        };

        $containerMock = $this->createMock(Container::class);

        $this->assertIsObject($provider->__invoke($containerMock));
        $this->assertInstanceOf(\stdClass::class, $provider->__invoke($containerMock));
    }
}