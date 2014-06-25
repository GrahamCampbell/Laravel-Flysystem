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

namespace GrahamCampbell\Tests\Flysystem\Managers;

use Mockery;
use GrahamCampbell\Flysystem\Managers\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the flysystem manager test class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class FlysystemManagerTest extends AbstractTestCase
{
    public function testConnectionName()
    {
        $config = array('driver' => 'local', 'path' => __DIR__);

        $manager = $this->getConfigManager($config);

        $this->assertEquals($manager->getConnections(), array());

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

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection();

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    public function testConnectionCache()
    {
        $config = array('driver' => 'local', 'path' => __DIR__, 'cache' => 'foo');

        $cache = array('driver' => 'illuminate', 'connection' => 'redis', 'key' => 'bar', 'ttl' => 300);

        $manager = $this->getConfigManagerCache($config, $cache);

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection('local');

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    public function testConnectionError()
    {
        $manager = $this->getManager();

        $config = array('driver' => 'error', 'path' => __DIR__);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::connections')->andReturn(array('local' => $config));

        $this->assertEquals($manager->getConnections(), array());

        $return = null;

        try {
            $manager->connection('error');
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    public function testConnectionErrorCache()
    {
        $manager = $this->getManager();

        $config = array('driver' => 'local', 'path' => __DIR__, 'cache' => 'foo');

        $cache = array('driver' => 'illuminate', 'connection' => 'redis', 'key' => 'bar', 'ttl' => 300);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::connections')->andReturn(array('local' => $config));

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('graham-campbell/flysystem::cache')->andReturn(array('error' => $cache));

        $this->assertEquals($manager->getConnections(), array());

        $return = null;

        try {
            $manager->connection('local');
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    protected function getManager()
    {
        $config = Mockery::mock('Illuminate\Config\Repository');
        $factory = Mockery::mock('GrahamCampbell\Flysystem\Filesystem\ConnectionFactory');

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
