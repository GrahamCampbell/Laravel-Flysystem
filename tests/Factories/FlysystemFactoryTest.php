<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Factories;

use GrahamCampbell\Flysystem\Factories\FlysystemFactory;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the filesystem factory test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class FlysystemFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local'];

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
        $this->assertInstanceOf('League\Flysystem\Filesystem', $return);
    }

    public function testMakeWithCache()
    {
        $config = ['driver' => 'local', 'cache' => ['driver' => 'redis', 'name' => 'illuminate'], 'name' => 'local'];

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactoryCache($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
        $this->assertInstanceOf('League\Flysystem\Filesystem', $return);
    }

    public function testMakeWithVisibility()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local', 'visibility' => 'public'];

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
        $this->assertInstanceOf('League\Flysystem\Filesystem', $return);
    }

    public function testMakeEventable()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local', 'eventable' => true];

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
        $this->assertInstanceOf('League\Flysystem\EventableFilesystem\EventableFilesystem', $return);
    }

    public function testMakeWithEventableCache()
    {
        $config = ['driver' => 'local', 'cache' => ['driver' => 'redis', 'name' => 'illuminate'], 'name' => 'local', 'eventable' => true];

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactoryCache($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
        $this->assertInstanceOf('League\Flysystem\EventableFilesystem\EventableFilesystem', $return);
    }

    public function testMakeEventableWithVisibility()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local', 'eventable' => true, 'visibility' => 'public'];

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf('League\Flysystem\FilesystemInterface', $return);
        $this->assertInstanceOf('League\Flysystem\EventableFilesystem\EventableFilesystem', $return);
    }

    public function testAdapter()
    {
        $factory = $this->getFlysystemFactory();

        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local'];

        $factory->getAdapter()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock('League\Flysystem\AdapterInterface'));

        $return = $factory->createAdapter($config);

        $this->assertInstanceOf('League\Flysystem\AdapterInterface', $return);
    }

    public function testCache()
    {
        $factory = $this->getFlysystemFactory();

        $manager = Mockery::mock('GrahamCampbell\Flysystem\FlysystemManager');

        $config = ['driver' => 'illuminate', 'connector' => 'redis', 'name' => 'foo'];

        $factory->getCache()->shouldReceive('make')->once()
            ->with($config, $manager)->andReturn(Mockery::mock('League\Flysystem\Cached\CacheInterface'));

        $return = $factory->createCache($config, $manager);

        $this->assertInstanceOf('League\Flysystem\Cached\CacheInterface', $return);
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

        $mock = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory[createAdapter,createCache]', [$adapter, $cache]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)
            ->andReturn($adapterMock);

        return $mock;
    }

    protected function getMockedFactoryCache($config, $manager)
    {
        $adapter = Mockery::mock('GrahamCampbell\Flysystem\Adapters\ConnectionFactory');
        $cache = Mockery::mock('GrahamCampbell\Flysystem\Cache\ConnectionFactory');

        $adapterMock = Mockery::mock('League\Flysystem\AdapterInterface');
        $cacheMock = Mockery::mock('League\Flysystem\Cached\CacheInterface');
        $cacheMock->shouldReceive('load')->once();

        $mock = Mockery::mock('GrahamCampbell\Flysystem\Factories\FlysystemFactory[createAdapter,createCache]', [$adapter, $cache]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)
            ->andReturn($adapterMock);

        $mock->shouldReceive('createCache')->once()
            ->with($config['cache'], $manager)
            ->andReturn($cacheMock);

        return $mock;
    }
}
