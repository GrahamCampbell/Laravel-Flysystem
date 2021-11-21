<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Cache\Storage;

use GrahamCampbell\Flysystem\Cache\Storage\IlluminateStorage;
use GrahamCampbell\TestBench\AbstractTestCase;
use Illuminate\Contracts\Cache\Store;
use Mockery;

/**
 * This is the illuminate storage test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class IlluminateStorageTest extends AbstractTestCase
{
    public function testload()
    {
        $storage = $this->getIlluminateStorage('foobarkey');

        $storage->getStore()->shouldReceive('get')->once()->with('foobarkey')->andReturn('herro');

        $storage->shouldReceive('setFromStorage')->once()->with('herro');

        $this->assertNull($storage->load());
    }

    public function testloadEmpty()
    {
        $storage = $this->getIlluminateStorage('foobarkey');

        $storage->getStore()->shouldReceive('get')->once()->with('foobarkey');

        $this->assertNull($storage->load());
    }

    public function testSave()
    {
        $storage = $this->getIlluminateStorage('foobarkey', 95);

        $storage->shouldReceive('getForStorage')->once()->andReturn('herro');

        $storage->getStore()->shouldReceive('put')->once()->with('foobarkey', 'herro', 95);

        $this->assertNull($storage->save());
    }

    public function testSaveForever()
    {
        $storage = $this->getIlluminateStorage('foobarkey');

        $storage->shouldReceive('getForStorage')->once()->andReturn('herro');

        $storage->getStore()->shouldReceive('forever')->once()->with('foobarkey', 'herro');

        $this->assertNull($storage->save());
    }

    protected function getIlluminateStorage($key, $ttl = null)
    {
        $client = Mockery::mock(Store::class);

        return Mockery::mock(IlluminateStorage::class.'[setFromStorage,getForStorage]', [$client, $key, $ttl]);
    }
}
