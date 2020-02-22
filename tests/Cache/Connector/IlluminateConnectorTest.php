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

namespace GrahamCampbell\Tests\Flysystem\Cache\Connector;

use GrahamCampbell\Flysystem\Cache\Connector\IlluminateConnector;
use GrahamCampbell\Flysystem\Cache\Storage\IlluminateStorage;
use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\RedisStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Factory;
use InvalidArgumentException;
use Mockery;

/**
 * This is the illuminate connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class IlluminateConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getIlluminateConnector();

        $repo = Mockery::mock(Repository::class);

        $connector->getCache()->shouldReceive('driver')->once()->andReturn($repo);

        $store = Mockery::mock(ArrayStore::class);

        $repo->shouldReceive('getStore')->once()->andReturn($store);

        $return = $connector->connect([]);

        $this->assertInstanceOf(IlluminateStorage::class, $return);
    }

    public function testConnectFull()
    {
        $connector = $this->getIlluminateConnector();

        $repo = Mockery::mock(Repository::class);

        $connector->getCache()->shouldReceive('driver')->once()->with('redis')->andReturn($repo);

        $store = Mockery::mock(RedisStore::class);

        $repo->shouldReceive('getStore')->once()->andReturn($store);

        $return = $connector->connect([
            'driver'    => 'illuminate',
            'connector' => 'redis',
            'key'       => 'bar',
            'ttl'       => 600,
        ]);

        $this->assertInstanceOf(IlluminateStorage::class, $return);
    }

    public function testConnectNoCacheFactory()
    {
        $connector = new IlluminateConnector();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Illuminate caching support not available.');

        $connector->connect([]);
    }

    protected function getIlluminateConnector()
    {
        $cache = Mockery::mock(Factory::class);

        return new IlluminateConnector($cache);
    }
}
