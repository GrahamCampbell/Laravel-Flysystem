<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Cache;

use GrahamCampbell\Flysystem\Cache\ConnectionFactory;
use GrahamCampbell\Flysystem\Cache\Connector\AdapterConnector;
use GrahamCampbell\Flysystem\Cache\Connector\IlluminateConnector;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Factory;
use InvalidArgumentException;
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
        $cache = Mockery::mock(Factory::class);
        $cache->shouldReceive('store')->once()->with('redis')->andReturn(new Repository(new ArrayStore()));
        $factory = new ConnectionFactory($cache);

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

    public function testCreateEmptyDriverConnector()
    {
        $manager = Mockery::mock(FlysystemManager::class);
        $factory = $this->getConnectionFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');

        $factory->createConnector([], $manager);
    }

    public function testCreateUnsupportedDriverConnector()
    {
        $manager = Mockery::mock(FlysystemManager::class);
        $factory = $this->getConnectionFactory();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported driver [unsupported].');

        $factory->createConnector(['driver' => 'unsupported'], $manager);
    }

    protected function getConnectionFactory()
    {
        $cache = Mockery::mock(Factory::class);

        return new ConnectionFactory($cache);
    }
}
