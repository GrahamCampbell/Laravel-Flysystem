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

use GrahamCampbell\Flysystem\Cache\IlluminateCache;
use GrahamCampbell\Flysystem\Cache\IlluminateConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\RedisStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Factory;
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

        $this->assertInstanceOf(IlluminateCache::class, $return);
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

        $this->assertInstanceOf(IlluminateCache::class, $return);
    }

    protected function getIlluminateConnector()
    {
        $cache = Mockery::mock(Factory::class);

        return new IlluminateConnector($cache);
    }
}
