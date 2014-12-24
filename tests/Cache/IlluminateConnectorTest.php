<?php

/*
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Flysystem\Cache;

use GrahamCampbell\Flysystem\Cache\IlluminateConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the illuminate connector test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
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
        $cache = Mockery::mock('Illuminate\Cache\CacheManager');

        return new IlluminateConnector($cache);
    }
}
