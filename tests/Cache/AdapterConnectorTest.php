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

use GrahamCampbell\Flysystem\Cache\AdapterConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the adapter connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AdapterConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAdapterConnector();

        $connector->getManager()->shouldReceive('getConnectionConfig')->once()
            ->with('local')->andReturn(['driver' => 'local', 'path' => __DIR__]);

        $factory = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory');

        $connector->getManager()->shouldReceive('getFactory')->once()->andReturn($factory);

        $adapter = Mockery::mock('League\Flysystem\Adapter\Local');

        $factory->shouldReceive('createAdapter')->once()
            ->with(['driver' => 'local', 'path' => __DIR__])->andReturn($adapter);

        $return = $connector->connect(['adapter' => 'local']);

        $this->assertInstanceOf('League\Flysystem\Cached\Storage\Adapter', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectError()
    {
        $connector = $this->getAdapterConnector();

        $connector->connect([]);
    }

    protected function getAdapterConnector()
    {
        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        return new AdapterConnector($manager);
    }
}
