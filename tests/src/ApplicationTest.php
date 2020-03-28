<?php

namespace Lookiero\Hiring\ConsoleTwitter\Tests\Unit;

use Lookiero\Hiring\ConsoleTwitter\Application;
use Lookiero\Hiring\ConsoleTwitter\Application\Container;
use Lookiero\Hiring\ConsoleTwitter\Application\Contracts\Kernel;
use Lookiero\Hiring\ConsoleTwitter\Tests\TestCase;

/**
 * Class ApplicationTest
 * @package Lookiero\Hiring\ConsoleTwitter\Tests
 */
class ApplicationTest extends TestCase
{

    /**
     * @return Application
     */
    public function testInstantiation()
    {
        $containerMock = $this->createMock(Container::class);

        $app = new Application($containerMock);

        $this->assertInstanceOf(Application::class, $app);
        $this->assertInstanceOf(Container::class, $app->getContainer());
        $this->assertEquals('cli', $app->getSAPI());

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     *
     * @depends testInstantiation
     */
    public function testKernel(Application $app)
    {
        $kernelMock = $this->createStub(Kernel::class);
        $this->assertInstanceOf(Application::class, $app->kernel('cli', $kernelMock));

        return $app;
    }

    /**
     * @param Application $app
     * @return Application
     *
     * @depends testKernel
     */
    public function testConfiguration(Application $app)
    {
        $this->assertInstanceOf(Application::class, $app->configure([]));
        return $app;
    }
}