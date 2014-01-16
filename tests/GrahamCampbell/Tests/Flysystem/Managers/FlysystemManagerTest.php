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
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class FlysystemManagerTest extends AbstractTestCase
{
    public function testConnectionName()
    {
        $manager = $this->getFlysystemManager();

        $config = array('driver' => 'local', 'path' => __DIR__);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem::connections')->andReturn(array('local' => $config));

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config, 'local')->andReturn(Mockery::mock('League\Flysystem\FilesystemInterface'));

        $return = $manager->connection('local');

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
    }

    public function testConnectionNull()
    {
        $manager = $this->getFlysystemManager();

        $config = array('driver' => 'local', 'path' => __DIR__);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem::default')->andReturn('local');

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem::connections')->andReturn(array('local' => $config));

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config, 'local')->andReturn(Mockery::mock('League\Flysystem\FilesystemInterface'));

        $return = $manager->connection();

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
    }

    protected function getFlysystemManager()
    {
        $config = Mockery::mock('Illuminate\Config\Repository');
        $factory = Mockery::mock('GrahamCampbell\Flysystem\Connectors\ConnectionFactory');

        return new FlysystemManager($config, $factory);
    }
}
