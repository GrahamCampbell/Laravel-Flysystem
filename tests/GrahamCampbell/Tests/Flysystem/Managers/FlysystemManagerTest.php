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
use GrahamCampbell\TestBench\Classes\AbstractTestCase;

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

        $return = $manager->reconnect('local');

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());

        $manager = $this->getFlysystemManager();

        $manager->disconnect('local');

        $this->assertEquals($manager->getConnections(), array());
    }

    public function testConnectionNull()
    {
        $config = array('driver' => 'local', 'path' => __DIR__);

        $manager = $this->getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('flysystem::default')->andReturn('local');

        $this->assertEquals($manager->getConnections(), array());

        $return = $manager->connection();

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());

        $return = $manager->reconnect();

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);

        $this->assertArrayHasKey('local', $manager->getConnections());

        $manager = $this->getFlysystemManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem::default')->andReturn('local');

        $manager->disconnect();

        $this->assertEquals($manager->getConnections(), array());
    }

    public function testConnectionError()
    {
        $manager = $this->getFlysystemManager();

        $config = array('driver' => 'error', 'path' => __DIR__);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem::connections')->andReturn(array('local' => $config));

        $this->assertEquals($manager->getConnections(), array());

        $return = null;

        try {
            $manager->connection('error');
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    public function testGetDefaultConnection()
    {
        $manager = $this->getFlysystemManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem::default')->andReturn('local');

        $return = $manager->getDefaultConnection();

        $this->assertEquals($return, 'local');
    }

    public function testSetDefaultConnection()
    {
        $manager = $this->getFlysystemManager();

        $manager->getConfig()->shouldReceive('set')->once()
            ->with('flysystem::default', 'local');

        $manager->setDefaultConnection('local');
    }

    public function testExtend()
    {
        $manager = $this->getFlysystemManager();

        $manager->extend('test', 'foo');
    }

    protected function getFlysystemManager()
    {
        $config = Mockery::mock('Illuminate\Config\Repository');
        $factory = Mockery::mock('GrahamCampbell\Flysystem\Connectors\ConnectionFactory');

        return new FlysystemManager($config, $factory);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getFlysystemManager();

        $manager->getConfig()->shouldReceive('get')->twice()
            ->with('flysystem::connections')->andReturn(array('local' => $config));

        $manager->getFactory()->shouldReceive('make')->twice()
            ->with($config, 'local')->andReturn(Mockery::mock('League\Flysystem\FilesystemInterface'));

        return $manager;
    }
}
