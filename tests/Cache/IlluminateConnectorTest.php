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

use GrahamCampbell\Flysystem\Cache\IlluminateConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the illuminate connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class IlluminateConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getIlluminateConnector();

        $repo = Mockery::mock('Illuminate\Cache\Repository');

        $connector->getCache()->shouldReceive('driver')->once()->andReturn($repo);

        $store = Mockery::mock('Illuminate\Cache\ArrayStore');

        $repo->shouldReceive('getStore')->once()->andReturn($store);

        $return = $connector->connect([]);

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Cache\IlluminateCache', $return);
    }

    public function testConnectFull()
    {
        $connector = $this->getIlluminateConnector();

        $repo = Mockery::mock('Illuminate\Cache\Repository');

        $connector->getCache()->shouldReceive('driver')->once()->with('redis')->andReturn($repo);

        $store = Mockery::mock('Illuminate\Cache\RedisStore');

        $repo->shouldReceive('getStore')->once()->andReturn($store);

        $return = $connector->connect([
            'driver'    => 'illuminate',
            'connector' => 'redis',
            'key'       => 'bar',
            'ttl'       => 600,
        ]);

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Cache\IlluminateCache', $return);
    }

    protected function getIlluminateConnector()
    {
        $cache = Mockery::mock('Illuminate\Contracts\Cache\Factory');

        return new IlluminateConnector($cache);
    }
}
