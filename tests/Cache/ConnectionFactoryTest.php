<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Cache;

use GrahamCampbell\Flysystem\Cache\ConnectionFactory;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the cache connection factory test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ConnectionFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($manager);

        $return = $factory->make(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'], $manager);

        $this->assertInstanceOf('League\Flysystem\Cached\CacheInterface', $return);
    }

    public function testCreateIlluminateConnector()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'], $manager);

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Cache\IlluminateConnector', $return);
    }

    public function testCreateAdapterConnector()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(['name' => 'foo', 'driver' => 'adapter', 'adapter' => 'local'], $manager);

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Cache\AdapterConnector', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateEmptyDriverConnector()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getConnectionFactory();

        $factory->createConnector([], $manager);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUnsupportedDriverConnector()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getConnectionFactory();

        $factory->createConnector(['driver' => 'unsupported'], $manager);
    }

    protected function getConnectionFactory()
    {
        $cache = Mockery::mock('Illuminate\Cache\CacheManager');

        return new ConnectionFactory($cache);
    }

    protected function getMockedFactory($manager)
    {
        $cache = Mockery::mock('Illuminate\Cache\CacheManager');

        $mock = Mockery::mock('GrahamCampbell\Flysystem\Cache\ConnectionFactory[createConnector]', [$cache]);

        $connector = Mockery::mock('GrahamCampbell\Flysystem\Cache\IlluminateConnector', [$cache]);

        $connector->shouldReceive('connect')->once()
            ->with(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'])
            ->andReturn(Mockery::mock('League\Flysystem\Cached\CacheInterface'));

        $mock->shouldReceive('createConnector')->once()
            ->with(['name' => 'foo', 'driver' => 'illuminate', 'connector' => 'redis'], $manager)
            ->andReturn($connector);

        return $mock;
    }
}
