<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem;

use GrahamCampbell\Flysystem\Adapter\ConnectionFactory as AdapterFactory;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory as CacheFactory;
use GrahamCampbell\Flysystem\FlysystemFactory;
use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAdapterFactoryIsInjectable()
    {
        $this->assertIsInjectable(AdapterFactory::class);
    }

    public function testCacheFactoryIsInjectable()
    {
        $this->assertIsInjectable(CacheFactory::class);
    }

    public function testFlysystemFactoryIsInjectable()
    {
        $this->assertIsInjectable(FlysystemFactory::class);
    }

    public function testFlysystemManagerIsInjectable()
    {
        $this->assertIsInjectable(FlysystemManager::class);
    }

    public function testBindings()
    {
        $this->assertIsInjectable(Filesystem::class);
        $this->assertIsInjectable(FilesystemInterface::class);

        $original = $this->app['flysystem.connection'];
        $this->app['flysystem']->reconnect();
        $new = $this->app['flysystem.connection'];

        $this->assertNotSame($original, $new);
        $this->assertEquals($original, $new);
    }
}
