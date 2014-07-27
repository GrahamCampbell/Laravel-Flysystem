<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Flysystem\Factories;

use Mockery;
use GrahamCampbell\TestBench\AbstractTestCase;
use GrahamCampbell\Flysystem\Factories\FlysystemFactory;

/**
 * This is the filesystem factory test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class FlysystemFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $config = array('driver' => 'local', 'path' => __DIR__, 'name' => 'local');

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
    }

    public function testMakeCache()
    {
        $config = array('driver' => 'local', 'cache' => array('driver' => 'redis', 'name' => 'illuminate'), 'name' => 'local');

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
    }

    public function testAdapter()
    {
        $factory = $this->getFlysystemFactory();

        $config = array('driver' => 'local', 'path' => __DIR__, 'name' => 'local');

        $factory->getAdapter()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock('League\Flysystem\AdapterInterface'));

        $return = $factory->createAdapter($config);

        $this->assertInstanceOf('League\Flysystem\AdapterInterface', $return);
    }

    public function testCache()
    {
        $factory = $this->getFlysystemFactory();

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $config = array('driver' => 'local', 'cache' => array('driver' => 'illuminate', 'connector' => 'redis', 'name' => 'foo'));

        $factory->getCache()->shouldReceive('make')->once()
            ->with($config['cache'], $manager)->andReturn(Mockery::mock('League\Flysystem\CacheInterface'));

        $return = $factory->createCache($config, $manager);

        $this->assertInstanceOf('League\Flysystem\CacheInterface', $return);
    }

    public function testCacheNull()
    {
        $factory = $this->getFlysystemFactory();

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $config = array('driver' => 'local', 'path' => __DIR__, 'name' => 'local');

        $return = $factory->createCache($config, $manager);

        $this->assertNull($return);
    }

    protected function getFlysystemFactory()
    {
        $adapter = Mockery::mock('GrahamCampbell\Flysystem\Adapters\ConnectionFactory');
        $cache = Mockery::mock('GrahamCampbell\Flysystem\Cache\ConnectionFactory');

        return new FlysystemFactory($adapter, $cache);
    }

    protected function getMockedFactory($config, $manager)
    {
        $adapter = Mockery::mock('GrahamCampbell\Flysystem\Adapters\ConnectionFactory');
        $cache = Mockery::mock('GrahamCampbell\Flysystem\Cache\ConnectionFactory');

        $adapterMock = Mockery::mock('League\Flysystem\AdapterInterface');

        $mock = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory[createAdapter,createCache]', array($adapter, $cache));

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)
            ->andReturn($adapterMock);

        $mock->shouldReceive('createCache')->once()
            ->with($config, $manager)
            ->andReturn(null);

        return $mock;
    }

    protected function getMockedFactoryCache($config, $manager)
    {
        $adapter = Mockery::mock('GrahamCampbell\Flysystem\Adapters\ConnectionFactory');
        $cache = Mockery::mock('GrahamCampbell\Flysystem\Cache\ConnectionFactory');

        $adapterMock = Mockery::mock('League\Flysystem\AdapterInterface');
        $cacheMock = Mockery::mock('League\Flysystem\CacheInterface');
        $cacheMock->shouldReceive('load')->once();

        $mock = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory[createAdapter,createCache]', array($adapter, $cache));

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)
            ->andReturn($adapterMock);

        $mock->shouldReceive('createCache')->once()
            ->with($config, $manager)
            ->andReturn($cacheMock);

        return $mock;
    }
}
