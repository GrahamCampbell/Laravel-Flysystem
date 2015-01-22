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

use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the illuminate cache test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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

        $cache->getClient()->shouldReceive('put')->once()->with('foobarkey', 'herro', 2);

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
        $client = Mockery::mock('Illuminate\Contracts\Cache\Store');

        return Mockery::mock(
            'GrahamCampbell\Flysystem\Cache\IlluminateCache[setFromStorage,getForStorage]',
            [$client, $key, $ttl]
        );
    }
}
