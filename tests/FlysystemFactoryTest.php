<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem;

use GrahamCampbell\Flysystem\Adapters\ConnectionFactory as AdapterFactory;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory as CacheFactory;
use GrahamCampbell\Flysystem\FlysystemFactory;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CacheInterface;
use League\Flysystem\EventableFilesystem\EventableFilesystem;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Mockery;

/**
 * This is the filesystem factory test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class FlysystemFactoryTest extends AbstractTestBenchTestCase
{
    public function testMake()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local'];

        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(Filesystem::class, $return);
        $this->assertInstanceOf(FilesystemInterface::class, $return);
    }

    public function testMakeWithCache()
    {
        $config = ['driver' => 'local', 'cache' => ['driver' => 'redis', 'name' => 'illuminate'], 'name' => 'local'];

        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactoryCache($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(FilesystemInterface::class, $return);
        $this->assertInstanceOf(Filesystem::class, $return);
    }

    public function testMakeWithVisibility()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local', 'visibility' => 'public'];

        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(FilesystemInterface::class, $return);
        $this->assertInstanceOf(Filesystem::class, $return);
    }

    public function testMakeEventable()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local', 'eventable' => true];

        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(FilesystemInterface::class, $return);
        $this->assertInstanceOf(EventableFilesystem::class, $return);
    }

    public function testMakeWithEventableCache()
    {
        $config = ['driver' => 'local', 'cache' => ['driver' => 'redis', 'name' => 'illuminate'], 'name' => 'local', 'eventable' => true];

        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactoryCache($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(FilesystemInterface::class, $return);
        $this->assertInstanceOf(EventableFilesystem::class, $return);
    }

    public function testMakeEventableWithVisibility()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local', 'eventable' => true, 'visibility' => 'public'];

        $manager = Mockery::mock(FlysystemManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(FilesystemInterface::class, $return);
        $this->assertInstanceOf(EventableFilesystem::class, $return);
    }

    public function testAdapter()
    {
        $factory = $this->getFlysystemFactory();

        $config = ['driver' => 'local', 'path' => __DIR__, 'name' => 'local'];

        $factory->getAdapter()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));

        $return = $factory->createAdapter($config);

        $this->assertInstanceOf(AdapterInterface::class, $return);
    }

    public function testCache()
    {
        $factory = $this->getFlysystemFactory();

        $manager = Mockery::mock(FlysystemManager::class);

        $config = ['driver' => 'illuminate', 'connector' => 'redis', 'name' => 'foo'];

        $factory->getCache()->shouldReceive('make')->once()
            ->with($config, $manager)->andReturn(Mockery::mock(CacheInterface::class));

        $return = $factory->createCache($config, $manager);

        $this->assertInstanceOf(CacheInterface::class, $return);
    }

    protected function getFlysystemFactory()
    {
        $adapter = Mockery::mock(AdapterFactory::class);
        $cache = Mockery::mock(CacheFactory::class);

        return new FlysystemFactory($adapter, $cache);
    }

    protected function getMockedFactory($config, $manager)
    {
        $adapter = Mockery::mock(AdapterFactory::class);
        $cache = Mockery::mock(CacheFactory::class);

        $mock = Mockery::mock(FlysystemFactory::class.'[createAdapter,createCache]', [$adapter, $cache]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));

        return $mock;
    }

    protected function getMockedFactoryCache($config, $manager)
    {
        $adapter = Mockery::mock(AdapterFactory::class);
        $cache = Mockery::mock(CacheFactory::class);

        $cacheMock = Mockery::mock(CacheInterface::class);
        $cacheMock->shouldReceive('load')->once();

        $mock = Mockery::mock(FlysystemFactory::class.'[createAdapter,createCache]', [$adapter, $cache]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));

        $mock->shouldReceive('createCache')->once()
            ->with($config['cache'], $manager)->andReturn($cacheMock);

        return $mock;
    }
}
