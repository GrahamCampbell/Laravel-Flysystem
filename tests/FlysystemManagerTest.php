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

namespace GrahamCampbell\Tests\Flysystem;

use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Mockery;

/**
 * This is the flysystem manager test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class FlysystemManagerTest extends AbstractTestBenchTestCase
{
    public function testConnectionName()
    {
        $config = array('driver' => 'local', 'path' => __DIR__);

        $manager = $this->getConfigManager($config);

        $this->assertSame(array(), $manager->getConnections());

        $return = $manager->connection('local');

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    public function testConnectionNull()
    {
        $config = array('driver' => 'local', 'path' => __DIR__);

        $manager = $this->getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::default')->andReturn('local');

        $this->assertSame(array(), $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    public function testConnectionCache()
    {
        $config = array('driver' => 'local', 'path' => __DIR__, 'cache' => 'foo');

        $cache = array('driver' => 'illuminate', 'connection' => 'redis', 'key' => 'bar', 'ttl' => 300);

        $manager = $this->getConfigManagerCache($config, $cache);

        $this->assertSame(array(), $manager->getConnections());

        $return = $manager->connection('local');

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectionError()
    {
        $manager = $this->getManager();

        $config = array('driver' => 'error', 'path' => __DIR__);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::connections')->andReturn(array('local' => $config));

        $this->assertSame(array(), $manager->getConnections());

        $return = null;

        $manager->connection('error');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectionErrorCache()
    {
        $manager = $this->getManager();

        $config = array('driver' => 'local', 'path' => __DIR__, 'cache' => 'foo');

        $cache = array('driver' => 'illuminate', 'connection' => 'redis', 'key' => 'bar', 'ttl' => 300);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::connections')->andReturn(array('local' => $config));

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::cache')->andReturn(array('error' => $cache));

        $this->assertSame(array(), $manager->getConnections());

        $return = null;

        $manager->connection('local');
    }

    protected function getManager()
    {
        $config = Mockery::mock('Illuminate\Config\Repository');
        $factory = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory');

        return new FlysystemManager($config, $factory);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::connections')->andReturn(array('local' => $config));

        $config['name'] = 'local';

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config, $manager)->andReturn(Mockery::mock('League\Flysystem\FilesystemInterface'));

        return $manager;
    }

    protected function getConfigManagerCache(array $config, array $cache)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::connections')->andReturn(array('local' => $config));

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::cache')->andReturn(array('foo' => $cache));

        $cache['name'] = 'foo';
        $config['name'] = 'local';
        $config['cache'] = $cache;

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config, $manager)->andReturn(Mockery::mock('League\Flysystem\FilesystemInterface'));

        return $manager;
    }
}
