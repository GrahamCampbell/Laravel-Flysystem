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

namespace GrahamCampbell\Tests\Flysystem\Cache;

use GrahamCampbell\Flysystem\Cache\IlluminateCache;
use GrahamCampbell\Flysystem\Cache\LifetimeHelper;
use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Contracts\Cache\Store;
use Mockery;

/**
 * This is the illuminate cache test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class IlluminateCacheTest extends AbstractTestCase
{
    public function testload()
    {
        $cache = $this->getIlluminateCache('foobarkey');

        $cache->getClient()->shouldReceive('get')->once()->with('foobarkey')->andReturn('herro');

        $cache->shouldReceive('setFromStorage')->once()->with('herro');

        $this->assertNull($cache->load());
    }

    public function testloadEmpty()
    {
        $cache = $this->getIlluminateCache('foobarkey');

        $cache->getClient()->shouldReceive('get')->once()->with('foobarkey');

        $this->assertNull($cache->load());
    }

    public function testSave()
    {
        $cache = $this->getIlluminateCache('foobarkey', 95);

        $cache->shouldReceive('getForStorage')->once()->andReturn('herro');

        $cache->getClient()->shouldReceive('put')->once()->with('foobarkey', 'herro', LifetimeHelper::isLegacy() ? 2 : 95);

        $this->assertNull($cache->save());
    }

    public function testSaveForever()
    {
        $cache = $this->getIlluminateCache('foobarkey');

        $cache->shouldReceive('getForStorage')->once()->andReturn('herro');

        $cache->getClient()->shouldReceive('forever')->once()->with('foobarkey', 'herro');

        $this->assertNull($cache->save());
    }

    protected function getIlluminateCache($key, $ttl = null)
    {
        $client = Mockery::mock(Store::class);

        return Mockery::mock(IlluminateCache::class.'[setFromStorage,getForStorage]', [$client, $key, $ttl]);
    }
}
