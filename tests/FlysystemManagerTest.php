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

use GrahamCampbell\Flysystem\Factories\FlysystemFactory;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Illuminate\Contracts\Config\Repository;
use League\Flysystem\FilesystemInterface;
use Mockery;

/**
 * This is the flysystem manager test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class FlysystemManagerTest extends AbstractTestBenchTestCase
{
    public function testConnectionName()
    {
        $config = ['driver' => 'local', 'path' => __DIR__];

        $manager = $this->getConfigManager($config);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('local');

        $this->assertInstanceOf(FilesystemInterface::class, $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    public function testConnectionNull()
    {
        $config = ['driver' => 'local', 'path' => __DIR__];

        $manager = $this->getConfigManager($config);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.default')->andReturn('local');

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf(FilesystemInterface::class, $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    public function testConnectionCache()
    {
        $config = ['driver' => 'local', 'path' => __DIR__, 'cache' => 'foo'];

        $cache = ['driver' => 'illuminate', 'connection' => 'redis', 'key' => 'bar', 'ttl' => 300];

        $manager = $this->getConfigManagerCache($config, $cache);

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection('local');

        $this->assertInstanceOf(FilesystemInterface::class, $return);

        $this->assertArrayHasKey('local', $manager->getConnections());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectionError()
    {
        $manager = $this->getManager();

        $config = ['driver' => 'error', 'path' => __DIR__];

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.connections')->andReturn(['local' => $config]);

        $this->assertSame([], $manager->getConnections());

        $return = null;

        $manager->connection('error');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectionErrorCache()
    {
        $manager = $this->getManager();

        $config = ['driver' => 'local', 'path' => __DIR__, 'cache' => 'foo'];

        $cache = ['driver' => 'illuminate', 'connection' => 'redis', 'key' => 'bar', 'ttl' => 300];

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.connections')->andReturn(['local' => $config]);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.cache')->andReturn(['error' => $cache]);

        $this->assertSame([], $manager->getConnections());

        $return = null;

        $manager->connection('local');
    }

    protected function getManager()
    {
        $config = Mockery::mock(Repository::class);
        $factory = Mockery::mock(FlysystemFactory::class);

        return new FlysystemManager($config, $factory);
    }

    protected function getConfigManager(array $config)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.connections')->andReturn(['local' => $config]);

        $config['name'] = 'local';

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config, $manager)->andReturn(Mockery::mock(FilesystemInterface::class));

        return $manager;
    }

    protected function getConfigManagerCache(array $config, array $cache)
    {
        $manager = $this->getManager();

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.connections')->andReturn(['local' => $config]);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('flysystem.cache')->andReturn(['foo' => $cache]);

        $cache['name'] = 'foo';
        $config['name'] = 'local';
        $config['cache'] = $cache;

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config, $manager)->andReturn(Mockery::mock(FilesystemInterface::class));

        return $manager;
    }
}
