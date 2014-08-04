<?php

/**
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

use Mockery;
use GrahamCampbell\Flysystem\Cache\AdapterConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the adapter connector test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class AdapterConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAdapterConnector();

        $connector->getManager()->shouldReceive('getConnectionConfig')->once()
            ->with('local')->andReturn(array('driver' => 'local', 'path' => __DIR__));

        $factory = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory');

        $connector->getManager()->shouldReceive('getFactory')->once()->andReturn($factory);

        $adapter = Mockery::mock('League\Flysystem\Adapter\Local');

        $factory->shouldReceive('createAdapter')->once()
            ->with(array('driver' => 'local', 'path' => __DIR__))->andReturn($adapter);

        $return = $connector->connect(array('adapter' => 'local'));

        $this->assertInstanceOf('League\Flysystem\Cache\Adapter', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectError()
    {
        $connector = $this->getAdapterConnector();

        $connector->connect(array());
    }

    protected function getAdapterConnector()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        return new AdapterConnector($manager);
    }
}
