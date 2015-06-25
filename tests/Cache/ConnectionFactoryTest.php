<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Cache;

use GrahamCampbell\Flysystem\Cache\AdapterConnector;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory;
use GrahamCampbell\Flysystem\Cache\IlluminateConnector;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Contracts\Cache\Factory;
use League\Flysystem\Cached\CacheInterface;
use Mockery;

/**
 * This is the cache connection factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ConnectionFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactory($manager);

        $return = $factory->make(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'], $manager);

        $this->assertInstanceOf(CacheInterface::class, $return);
    }

    public function testCreateIlluminateConnector()
    {
        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'], $manager);

        $this->assertInstanceOf(IlluminateConnector::class, $return);
    }

    public function testCreateAdapterConnector()
    {
        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(['name' => 'foo', 'driver' => 'adapter', 'adapter' => 'local'], $manager);

        $this->assertInstanceOf(AdapterConnector::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage A driver must be specified.
     */
    public function testCreateEmptyDriverConnector()
    {
        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getConnectionFactory();

        $factory->createConnector([], $manager);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported driver [unsupported].
     */
    public function testCreateUnsupportedDriverConnector()
    {
        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getConnectionFactory();

        $factory->createConnector(['driver' => 'unsupported'], $manager);
    }

    protected function getConnectionFactory()
    {
        $cache = Mockery::mock(Factory::class);

        return new ConnectionFactory($cache);
    }

    protected function getMockedFactory($manager)
    {
        $cache = Mockery::mock(Factory::class);

        $mock = Mockery::mock(ConnectionFactory::class.'[createConnector]', [$cache]);

        $connector = Mockery::mock(IlluminateConnector::class, [$cache]);

        $connector->shouldReceive('connect')->once()
            ->with(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'])
            ->andReturn(Mockery::mock(CacheInterface::class));

        $mock->shouldReceive('createConnector')->once()
            ->with(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'], $manager)
            ->andReturn($connector);

        return $mock;
    }
}
