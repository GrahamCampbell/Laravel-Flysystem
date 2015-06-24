<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Cache;

use GrahamCampbell\Flysystem\Cache\AdapterConnector;
use GrahamCampbell\Flysystem\FlysystemFactory;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\Storage\Adapter;
use Mockery;

/**
 * This is the adapter connector test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class AdapterConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAdapterConnector();

        $connector->getManager()->shouldReceive('getConnectionConfig')->once()
            ->with('local')->andReturn(['driver' => 'local', 'path' => __DIR__]);

        $factory = Mockery::mock(FlysystemFactory::class);

        $connector->getManager()->shouldReceive('getFactory')->once()->andReturn($factory);

        $adapter = Mockery::mock(Local::class);

        $factory->shouldReceive('createAdapter')->once()
            ->with(['driver' => 'local', 'path' => __DIR__])->andReturn($adapter);

        $return = $connector->connect(['adapter' => 'local']);

        $this->assertInstanceOf(Adapter::class, $return);
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
        $manager = Mockery::mock(FlysystemManager::class);

        return new AdapterConnector($manager);
    }
}
